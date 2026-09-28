<?php

use App\Services\MedicineForecastService as Svc;

it('applies a damped upward trend to a rising series', function () {
    // slope = 10/month; adjustment = 10 * 2 * 0.5 = 10
    $r = Svc::computeTrend([10, 20, 30, 40, 50, 60], 50);
    expect($r['status'])->toBe('applied')
        ->and(round($r['slope'], 2))->toBe(10.0)
        ->and(round($r['adjustment'], 2))->toBe(10.0);
});

it('applies a downward trend to a falling series', function () {
    $r = Svc::computeTrend([60, 50, 40, 30, 20, 10], 30);
    expect($r['status'])->toBe('applied')
        ->and($r['adjustment'])->toBeLessThan(0);
});

it('caps the adjustment at half of the moving average', function () {
    // raw adjustment would be 100, cap = 20 * 0.5 = 10
    $r = Svc::computeTrend([0, 50, 100, 150, 200, 250], 20);
    expect($r['adjustment'])->toBe(10.0);
});

it('does not apply a trend to sparse (mostly zero) demand', function () {
    $r = Svc::computeTrend([0, 0, 0, 0, 55, 125], 60);
    expect($r['status'])->toBe('sparse')->and($r['adjustment'])->toBe(0.0);
});

it('does not apply a trend with too little history', function () {
    $r = Svc::computeTrend([10, 20, 30], 20);
    expect($r['status'])->toBe('short_history')->and($r['adjustment'])->toBe(0.0);
});

it('gives no adjustment for a flat series', function () {
    $r = Svc::computeTrend([20, 20, 20, 20, 20, 20], 20);
    expect($r['status'])->toBe('applied')->and($r['adjustment'])->toBe(0.0);
});

// ---- Unmet demand (no double counting) ----

it('counts unmet demand only from the latest record of each prescription', function () {
    // Rx 7: first attempt short by 10, follow-up short by 4 -> only the 4 counts.
    $rows = [
        ['id' => 1, 'prescription_id' => 7, 'unfulfilled' => 10],
        ['id' => 2, 'prescription_id' => 7, 'unfulfilled' => 4],
        ['id' => 3, 'prescription_id' => 8, 'unfulfilled' => 0],
    ];
    expect(Svc::resolveUnmetDemand($rows))->toBe([1 => 0, 2 => 4, 3 => 0]);
});

it('counts nothing once a follow-up fully dispenses the remainder', function () {
    $rows = [
        ['id' => 1, 'prescription_id' => 7, 'unfulfilled' => 10],
        ['id' => 2, 'prescription_id' => 7, 'unfulfilled' => 0],
    ];
    expect(array_sum(Svc::resolveUnmetDemand($rows)))->toBe(0);
});

it('ignores unmet demand from cancelled prescriptions', function () {
    $rows = [['id' => 1, 'prescription_id' => 7, 'unfulfilled' => 10]];
    expect(Svc::resolveUnmetDemand($rows, [7]))->toBe([1 => 0]);
});

it('counts unmet demand on records with no prescription link', function () {
    $rows = [['id' => 1, 'prescription_id' => null, 'unfulfilled' => 6]];
    expect(Svc::resolveUnmetDemand($rows))->toBe([1 => 6]);
});

// ---- Range and confidence ----

it('gives High confidence for six steady active months', function () {
    $r = Svc::computeRangeAndConfidence([40, 42, 38, 41, 39, 40], 40, 'sufficient');
    expect($r['confidence'])->toBe('High')
        ->and($r['range_low'])->toBeLessThanOrEqual(40)
        ->and($r['range_high'])->toBeGreaterThanOrEqual(40)
        ->and($r['reasons'])->toBe([]);
});

it('gives Low confidence and explains why for sparse demand', function () {
    $r = Svc::computeRangeAndConfidence([0, 0, 0, 0, 55, 125], 60, 'sufficient');
    expect($r['confidence'])->toBe('Low')->and($r['reasons'])->not->toBeEmpty();
});

it('gives Medium confidence for moderately steady partial-year data', function () {
    $r = Svc::computeRangeAndConfidence([30, 40, 35, 45], 40, 'sufficient');
    expect($r['confidence'])->toBe('Medium');
});

it('floors the range at zero and returns no confidence without data', function () {
    $r = Svc::computeRangeAndConfidence([0, 0, 90], 10, 'sufficient');
    expect($r['range_low'])->toBe(0);
    $none = Svc::computeRangeAndConfidence([], 0, 'insufficient');
    expect($none['confidence'])->toBe('None')->and($none['range_low'])->toBeNull();
});