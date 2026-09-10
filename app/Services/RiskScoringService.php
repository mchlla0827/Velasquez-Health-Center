<?php

namespace App\Services;

use App\Models\NcdAssessment;

/**
 * RiskScoringService
 * ===================
 * A transparent, deterministic, RULE-BASED acuity scoring engine for
 * initial patient triage. This is NOT a machine-learning or AI model -
 * every point value below is an explicit, human-readable rule.
 *
 * IMPORTANT - ABOUT THE POINT VALUES BELOW:
 * This project's thesis specifies WHICH factors should influence triage
 * (age/vulnerability, vital signs, symptoms, and existing risk factors -
 * with particular attention to elderly patients) but does not specify
 * exact numerical weights or thresholds. The values in this file are a
 * DRAFT, RESEARCHER-DEFINED rule set based on general, widely-used
 * triage conventions (e.g. standard adult fever/hypertension ranges).
 * They are a starting point for the research team to review, adjust,
 * and formally document as their own methodology - not a claim of
 * clinical validation.
 *
 * DESIGN PRINCIPLES:
 * - Every factor is additive and explainable (see $factors in the result).
 * - Two historically-recognized emergency symptoms (difficulty breathing,
 *   chest pain) are treated as an automatic HIGH override, matching both
 *   the system's original behavior and standard triage practice - everyone
 *   else is scored cumulatively.
 * - Missing/unparseable vitals are SKIPPED, never assumed normal.
 * - Age is one contributing factor among several - NOT an automatic
 *   HIGH classification by itself.
 * - This score is a DECISION-SUPPORT tool for queue prioritization.
 *   It does not replace the attending doctor's clinical judgment.
 */
class RiskScoringService
{
    // ---- Thresholds (DRAFT - adjust and document per your methodology) ----
    const THRESHOLD_HIGH = 10;
    const THRESHOLD_MODERATE = 5;

    // ---- Symptom points (DRAFT) ----
    // Higher-acuity symptoms also trigger the automatic HIGH override below.
    const SYMPTOM_POINTS = [
        'difficulty breathing' => 6,
        'chest pain' => 6,
        'fever' => 3,
        'cough' => 1,
        'body pain' => 1,
        'headache' => 1,
    ];

    // Symptoms that, on their own, force a HIGH classification regardless
    // of total score - matching the system's original safety behavior.
    const RED_FLAG_SYMPTOMS = ['difficulty breathing', 'chest pain'];

    /**
     * Run the full assessment and return a structured, explainable result.
     *
     * @param array $vitals ['temp' => string|null, 'bp' => string|null, 'weight' => string|null, 'height' => string|null]
     * @param array $symptoms lowercase symptom strings, e.g. ['fever', 'cough']
     * @param int|null $age patient's current age in years
     * @param NcdAssessment|null $latestNcd patient's most recent NCD assessment, if any
     * @return array{score:int, level:string, priority:int, factors:array, red_flag:bool}
     */
    public function assess(array $vitals, array $symptoms, ?int $age, ?NcdAssessment $latestNcd = null): array
    {
        $score = 0;
        $factors = [];
        $redFlag = false;

        $symptoms = array_map('strtolower', array_map('trim', $symptoms));

        // ---- A. Symptoms ----
        foreach ($symptoms as $symptom) {
            if (isset(self::SYMPTOM_POINTS[$symptom])) {
                $points = self::SYMPTOM_POINTS[$symptom];
                $score += $points;
                $factors[] = ucfirst($symptom) . " (+{$points})";
            }
            if (in_array($symptom, self::RED_FLAG_SYMPTOMS, true)) {
                $redFlag = true;
            }
        }

        // ---- B. Vital signs (skip if missing/unparseable - never assume normal) ----
        $temp = $this->parseTemperature($vitals['temp'] ?? null);
        if ($temp !== null) {
            if ($temp >= 39.0) {
                $score += 4;
                $factors[] = "High fever, {$temp}\u{00B0}C (+4)";
            } elseif ($temp >= 37.5) {
                $score += 2;
                $factors[] = "Fever, {$temp}\u{00B0}C (+2)";
            }
        }

        [$systolic, $diastolic] = $this->parseBloodPressure($vitals['bp'] ?? null);
        if ($systolic !== null && $diastolic !== null) {
            if ($systolic >= 160 || $diastolic >= 100) {
                $score += 4;
                $factors[] = "Significantly elevated blood pressure, {$systolic}/{$diastolic} (+4)";
            } elseif ($systolic >= 130 || $diastolic >= 85) {
                $score += 2;
                $factors[] = "Elevated blood pressure, {$systolic}/{$diastolic} (+2)";
            }
        }

        // ---- C. Age / vulnerability ----
        if ($age !== null) {
            if ($age < 1) {
                $score += 3;
                $factors[] = "Infant (under 1 year) (+3)";
            } elseif ($age < 5) {
                $score += 2;
                $factors[] = "Young child (1-4 years) (+2)";
            } elseif ($age >= 75) {
                $score += 3;
                $factors[] = "Elderly (75+ years) (+3)";
            } elseif ($age >= 60) {
                $score += 2;
                $factors[] = "Elderly (60+ years) (+2)";
            }
        }

        // ---- D. Existing risk factors / conditions (from latest NCD assessment) ----
        if ($latestNcd) {
            if ($latestNcd->is_diabetic) {
                $score += 2;
                $factors[] = "Existing diabetes (+2)";
            }
            if ($latestNcd->is_hypertensive) {
                $score += 2;
                $factors[] = "Existing hypertension (+2)";
            }
            if ($latestNcd->has_copd) {
                $score += 2;
                $factors[] = "Existing chronic lung condition (+2)";
            }
            if ($latestNcd->has_cancer) {
                $score += 1;
                $factors[] = "Existing cancer diagnosis (+1)";
            }
        }

        // ---- Classification ----
        if ($redFlag) {
            $level = 'High';
            if (empty(array_filter($factors, fn($f) => str_contains($f, 'breathing') || str_contains($f, 'Chest pain')))) {
                $factors[] = 'Red-flag symptom present';
            }
        } elseif ($score >= self::THRESHOLD_HIGH) {
            $level = 'High';
        } elseif ($score >= self::THRESHOLD_MODERATE) {
            $level = 'Medium';
        } else {
            $level = 'Low';
        }

        $priority = match ($level) {
            'High' => 1,
            'Medium' => 2,
            default => 3,
        };

        if (empty($factors)) {
            $factors[] = 'No significant risk factors identified';
        }

        return [
            'score' => $score,
            'level' => $level,
            'priority' => $priority,
            'factors' => $factors,
            'red_flag' => $redFlag,
        ];
    }

    /**
     * Parse a free-text temperature field (e.g. "37.5", "37.5 C", "37.5 degrees C").
     * Returns null (not 0/normal) if it can't be confidently parsed.
     */
    private function parseTemperature(?string $raw): ?float
    {
        if (!$raw || trim($raw) === '') {
            return null;
        }
        if (preg_match('/(\d{2}(?:\.\d+)?)/', $raw, $m)) {
            $value = (float) $m[1];
            // Sanity range for a human body temperature reading in Celsius.
            if ($value >= 30 && $value <= 45) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Parse a free-text blood pressure field (e.g. "120/80", "120 / 80 mmHg").
     * Returns [null, null] if it can't be confidently parsed as systolic/diastolic.
     */
    private function parseBloodPressure(?string $raw): array
    {
        if (!$raw || trim($raw) === '') {
            return [null, null];
        }
        if (preg_match('/(\d{2,3})\s*\/\s*(\d{2,3})/', $raw, $m)) {
            $systolic = (int) $m[1];
            $diastolic = (int) $m[2];
            if ($systolic >= 60 && $systolic <= 260 && $diastolic >= 30 && $diastolic <= 160) {
                return [$systolic, $diastolic];
            }
        }
        return [null, null];
    }
}