<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <meta charset="UTF-8">
    <title>Integrated NCD Risk Assessment - {{ $patient->first_name }}</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #E5E7EB; color: #1F2937; padding-bottom: 50px; }
        .container { max-width: 1000px; margin: 40px auto; background: white; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden; }
        .header { background: #1A73E8; color: white; padding: 25px 30px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .sub-header { background: #F3F4F6; padding: 15px 30px; border-bottom: 2px solid #D1D5DB; display: flex; justify-content: space-between; font-size: 14px; }
        .form-body { padding: 30px; }
        .section-title { background: #374151; color: white; padding: 10px 15px; font-size: 15px; font-weight: bold; margin: 25px 0 15px; border-radius: 4px; }
        .sub-section-title { font-size: 14px; font-weight: bold; color: #111827; border-bottom: 1px solid #D1D5DB; padding-bottom: 5px; margin: 15px 0; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 12px; }
        .form-group label { font-size: 13px; font-weight: bold; color: #4B5563; margin-bottom: 4px; }
        .form-group input, .form-group select { padding: 6px 10px; border: 1px solid #D1D5DB; border-radius: 4px; font-size: 13px; }
        .check-group { display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 4px; cursor: pointer; }
        .radio-inline { display: flex; gap: 12px; font-size: 12px; margin-top: 5px; }
        .doh-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 14px; table-layout: fixed; }
        .doh-table th, .doh-table td { border: 1px solid #D1D5DB; padding: 8px; text-align: left; vertical-align: top; overflow: hidden; }
        .doh-table th { background: #F9FAFB; font-weight: bold; }
        .col-yes-no { width: 50px; text-align: center !important; }
        .risk-area { background: #FDF2F2; }
        .paper-line { border: none; border-bottom: 1px solid #374151; outline: none; font-weight: bold; width: 100%; }
        .footer { padding: 20px 30px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; justify-content: flex-end; gap: 12px; position: sticky; bottom: 0; z-index: 100; }
        .btn-save { padding: 10px 25px; background: #10B981; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Integrated NCD Risk Assessment Form</h2>
        <p>Official DOH Electronic Version • Patient Record Integration</p>
    </div>
    
    <div class="sub-header">
        <div><b>Patient:</b> {{ $patient->last_name }}, {{ $patient->first_name }}</div>
        <div><b>ID:</b> {{ $patient->patient_id }}</div>
        <div><b>Date:</b> {{ date('m/d/Y') }}</div>
    </div>

    <form action="{{ route('ncd.store', $patient->id) }}" method="POST" class="form-body">
        @csrf

        <div class="section-title" style="margin-top: 0;">Part II. Past Medical History</div>
        <table class="doh-table">
            <tr>
                <th style="width: 25%;">1. Karamdaman (Conditions)</th>
                <th style="width: 20%;">1.3 Taon Nalaman</th>
                <th style="width: 30%;">1.4 Ininom na Gamot</th>
                <th style="width: 25%;">Risk Status Indicator</th>
            </tr>
            @php 
                $history = [
                    ['is_diabetic','Diabetes','risk_dm','DM'],
                    ['is_hypertensive','Hypertension','risk_hpn','HPN'],
                    ['has_copd','COPD','risk_copd','COPD']
                ]; 
            @endphp
            @foreach($history as $h)
            <tr>
                <td><label class="check-group"><input type="checkbox" name="{{$h[0]}}" value="Yes"> {{$h[1]}}</label></td>
                <td><input type="text" name="{{$h[0]}}_year" class="paper-line" placeholder="YYYY"></td>
                <td><input type="text" name="{{$h[0]}}_meds" class="paper-line" placeholder="Gamot"></td>
                <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="{{$h[2]}}" value="Yes"> {{$h[3]}}</label></td>
            </tr>
            @endforeach
            <tr>
                <td>
                    <label class="check-group"><input type="checkbox" name="has_cancer" value="Yes"> Cancer</label>
                    <input type="text" name="cancer_site_condition" placeholder="Site..." style="width:85%; margin-left:20px; font-size:10px;" class="paper-line">
                </td>
                <td><input type="text" name="cancer_year" class="paper-line" placeholder="YYYY"></td>
                <td><input type="text" name="cancer_meds" class="paper-line" placeholder="Gamot"></td>
                <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="risk_cancer" value="Yes"> Cancer</label></td>
            </tr>
            <tr>
                <td><label class="check-group"><input type="checkbox" name="has_eye_disease" value="Yes"> Sakit sa mata</label></td>
                <td><input type="text" name="eye_year" class="paper-line" placeholder="YYYY"></td>
                <td><input type="text" name="eye_meds" class="paper-line" placeholder="Gamot"></td>
                <td></td>
            </tr>
        </table>

        <table class="doh-table">
            <tr style="background:#F3F4F6;"><th>2. Pananakit ng dibdib (Chest Pain / Angina)</th><th class="col-yes-no">Oo</th><th class="col-yes-no">Hindi</th></tr>
            <tr><td>2.1 Nakakaramdam ka ba ng pananakit o kabigatan sa dibdib? (Kung Hindi, Go to Q2.8)</td><td class="col-yes-no"><input type="radio" name="cp1" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp1" value="No"></td></tr>
            <tr><td>2.2 Ang sakit ba ay nasa gitna, kaliwang bahagi hanggang kaliwang braso?</td><td class="col-yes-no"><input type="radio" name="cp2" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp2" value="No"></td></tr>
            <tr><td>2.3 Nararamdaman mo ba ito kung nagmamadali o naglalakad ng mabilis?</td><td class="col-yes-no"><input type="radio" name="cp3" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp3" value="No"></td></tr>
            <tr><td>2.4 Napapatigil ka ba sa paglalakad kapag sumasakit ang iyong dibdib?</td><td class="col-yes-no"><input type="radio" name="cp4" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp4" value="No"></td></tr>
            <tr><td>2.5 Nawawala ba ang sakit kapag hindi ka kumikilos o nag-nitroglycerin?</td><td class="col-yes-no"><input type="radio" name="cp5" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp5" value="No"></td></tr>
            <tr><td>2.6 Nawawala ba ang sakit sa loob ng 10 minuto?</td><td class="col-yes-no"><input type="radio" name="cp6" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp6" value="No"></td></tr>
            <tr><td>2.7 Sakit sa dibdib na tumatagal higit sa 30 minuto?</td><td class="col-yes-no"><input type="radio" name="cp7" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp7" value="No"></td></tr>
            <tr style="background:#FEE2E2;"><td colspan="3" style="color:#991B1B; font-weight:bold; font-size:10px;">* Kung Oo sa 2.4-2.7, dalhin agad sa doktor (Impending Heart Attack).</td></tr>
            <tr><td>2.8 Hirap sa pagsasalita, panghihina ng braso/binti, o pamamanhid sa kalahating bahagi?</td><td class="col-yes-no"><input type="radio" name="cp8" value="Yes"></td><td class="col-yes-no"><input type="radio" name="cp8" value="No"></td></tr>
        </table>

        <div class="section-title">Part III. Assessment of Risk Factors</div>
        
        <table class="doh-table">
            <tr style="background:#E5E7EB;"><th colspan="3">B.1 Nutrisyon</th><th style="width:25%">Risk Status</th></tr>
            <tr><td colspan="3"><b>1. Madalas mo bang kainin ang mga sumusunod kada araw?</b></td><td rowspan="9" class="risk-area">
                <label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_diet" value="Yes"> Unhealthy Diet</label>
                <label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_salt" value="Yes"> High Salt Intake</label>
            </td></tr>
            @foreach(['Gulay','Prutas','Isda','Karne','Processed food'] as $food)
            <tr><td>{{$food}}</td><td class="col-yes-no"><input type="radio" name="diet_{{strtolower($food)}}" value="Yes"></td><td class="col-yes-no"><input type="radio" name="diet_{{strtolower($food)}}" value="No"></td></tr>
            @endforeach
            <tr><td colspan="3"><b>2. Kumakain ka ba ng >2x kada linggo ng:</b></td></tr>
            @foreach(['Maalat','Matatamis','Mamantika'] as $taste)
            <tr><td>{{$taste}} na pagkain</td><td class="col-yes-no"><input type="radio" name="diet_{{strtolower($taste)}}" value="Yes"></td><td class="col-yes-no"><input type="radio" name="diet_{{strtolower($taste)}}" value="No"></td></tr>
            @endforeach
        </table>

        <div class="sub-section-title">B.2 Alcohol</div>
        <table class="doh-table" style="margin-bottom:0; border-bottom:none;">
            <tr>
                <td><b>1. Umiinom ka ba?</b><div class="radio-inline"><label><input type="radio" name="alc_u" value="Oo"> Oo</label><label><input type="radio" name="alc_u" value="Hindi"> Hindi</label></div></td>
                <td><b>Kung hindi, kailan tumigil?</b><div class="radio-inline"><label><input type="radio" name="alc_q" value="<1"> < 1 taon</label><label><input type="radio" name="alc_q" value=">=1"> >= 1 taon</label></div></td>
            </tr>
            <tr><td colspan="2"><b>2. Anong klase?</b> <div class="radio-inline"><label><input type="checkbox" name="alc_t[]" value="Beer"> Beer</label><label><input type="checkbox" name="alc_t[]" value="Wine"> Wine</label><label><input type="checkbox" name="alc_t[]" value="Gin"> Whisky/Brandy</label></div></td></tr>
        </table>
        <div style="display:grid; grid-template-columns: 2.5fr 1.5fr 1fr 1fr; border:1px solid #D1D5DB; border-top:none; background:white;">
            <div style="padding:10px; border-right:1px solid #D1D5DB;"><label style="font-size:10px; font-weight:bold;">3. GAANO KARAMI / DAY?</label>
                <div class="grid-3" style="margin-top:5px;">
                    <div><span style="font-size:9px">Beer</span><select name="amt_b" style="width:100%"><option value="">-</option><option value="1">1 bot</option></select></div>
                    <div><span style="font-size:9px">Wine</span><select name="amt_w" style="width:100%"><option value="">-</option><option value="1">1 gls</option></select></div>
                    <div><span style="font-size:9px">Shots</span><select name="amt_s" style="width:100%"><option value="">-</option><option value="1">1 sht</option></select></div>
                </div>
            </div>
            <div style="padding:10px; border-right:1px solid #D1D5DB;"><label style="font-size:10px; font-weight:bold;">4. GAANO KADALAS / WK?</label>
                <div style="margin-top:5px; display:flex; flex-direction:column; gap:2px;">
                    <label style="font-size:10px"><input type="radio" name="alc_f" value="1"> 1-3x</label>
                    <label style="font-size:10px"><input type="radio" name="alc_f" value="4"> 4x</label>
                    <label style="font-size:10px"><input type="radio" name="alc_f" value="5"> 5+x</label>
                </div>
            </div>
            <div style="padding:10px; border-right:1px solid #D1D5DB;"><label style="font-size:10px; font-weight:bold;">5. ILANG BOTE?</label>
                <div style="margin-top:5px;"><label style="font-size:10px"><input type="radio" name="alc_b" value="<4">< 4</label><br><label style="font-size:10px"><input type="radio" name="alc_b" value="5"> 5 o higit</label></div>
            </div>
            <div style="padding:10px; background:#FDF2F2; text-align:center; display:flex; flex-direction:column; justify-content:center;">
                <label style="color:#991B1B; font-weight:bold; font-size:11px;"><input type="checkbox" name="r_binge" value="Yes"> BINGE DRINKER</label>
            </div>
        </div>

        <div class="section-title">Part IV. Risk Screening</div>
        <table class="doh-table" style="table-layout: fixed;">
            <thead>
                <tr><th style="width:20%">Assessment</th><th style="width:35%">Measurements</th><th style="width:25%">Status</th><th style="width:20%">Risk Status</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2"><b>4.1 Anthropometric</b><br><small>Formula BMI: (Wt/Ht/Ht)x10,000</small></td>
                    <td>
                        <div style="display:flex; gap:5px; margin-bottom:5px;">
                            <div style="flex:1"><label style="font-size:9px">Wt(kg)</label><input type="text" name="w" style="width:90%"></div>
                            <div style="flex:1"><label style="font-size:9px">Ht(cm)</label><input type="text" name="h" style="width:90%"></div>
                        </div>
                        <label style="font-size:9px">BMI Result</label><input type="text" name="bmi" style="width:95%">
                    </td>
                    <td>
                        <label style="font-size:10px"><input type="radio" name="bmi_s" value="U"> Under(<18.5)</label><br>
                        <label style="font-size:10px"><input type="radio" name="bmi_s" value="N"> Normal(18.5-22.9)</label><br>
                        <label style="font-size:10px"><input type="radio" name="bmi_s" value="O"> Over(23-24.9)</label><br>
                        <label style="font-size:10px"><input type="radio" name="bmi_s" value="B"> Obese(≥25)</label>
                    </td>
                    <td class="risk-area">
                        <label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_over" value="Y"> Overweight</label>
                        <label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_obese" value="Y"> Obese</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="display:flex; gap:5px; margin-bottom:5px;">
                            <div style="flex:1"><label style="font-size:9px">Waist(cm)</label><input type="text" name="waist" style="width:90%"></div>
                            <div style="flex:1"><label style="font-size:9px">Hip(cm)</label><input type="text" name="hip" style="width:90%"></div>
                        </div>
                        <label style="font-size:9px">W/H Ratio</label><input type="text" name="whr" style="width:95%">
                    </td>
                    <td><label style="font-size:10px"><input type="radio" name="whr_s" value="N"> No Risk</label><br><label style="font-size:10px"><input type="radio" name="whr_s" value="R"> At Risk</label></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_whr" value="Y"> At Risk</label></td>
                </tr>

                <tr>
                    <td rowspan="2"><b>4.2 Blood Sugar</b></td>
                    <td><small>Fasting (CBG):</small><input type="text" name="fbs" style="width:95%"><br><small>Venous Ex:</small><input type="text" name="vn" style="width:95%"></td>
                    <td><label style="font-size:10px"><input type="radio" name="fbs_s" value="N"> Normal</label><br><label style="font-size:10px"><input type="radio" name="fbs_s" value="I"> Impaired</label><br><label style="font-size:10px"><input type="radio" name="fbs_s" value="D"> DM</label></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_predm" value="Y"> Pre-Diabetes</label></td>
                </tr>
                <tr>
                    <td><small>Random Blood Sugar:</small><br><label style="font-size:10px"><input type="radio" name="rbs_s" value="N"> <140</label><br><label style="font-size:10px"><input type="radio" name="rbs_s" value="A"> ≥200</label></td>
                    <td><small>Check classic symptoms:</small><br><label style="font-size:9px"><input type="checkbox" name="s_pol" value="Y"> Polyuria</label><br><label style="font-size:9px"><input type="checkbox" name="s_wgt" value="Y"> Weight Loss</label></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_dm_f" value="Y"> DM</label></td>
                </tr>

                <tr>
                    <td><b>4.3 Blood Pressure</b></td>
                    <td><div style="display:flex; gap:2px"><input type="text" name="bp_l" placeholder="L" style="width:30%"><input type="text" name="bp_r" placeholder="R" style="width:30%"><input type="text" name="bp_b" placeholder="Base" style="width:35%; border-bottom:2px solid red"></div></td>
                    <td><label style="font-size:9px"><input type="radio" name="bp_s" value="N"> Normal</label><br><label style="font-size:9px"><input type="radio" name="bp_s" value="P"> Pre-HPN</label></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_hpn_f" value="Y"> HPN</label></td>
                </tr>
                <tr>
                    <td><b>4.4 Cholesterol</b></td>
                    <td><input type="text" name="chol" style="width:95%"></td>
                    <td><label style="font-size:10px"><input type="radio" name="ch_s" value="N"> Normal</label><br><label style="font-size:10px"><input type="radio" name="ch_s" value="R"> At Risk</label></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_chol" value="Y"> Elevated</label></td>
                </tr>
                <tr>
                    <td><b>4.5 Urine Dipstick</b></td>
                    <td>Protein: <input type="radio" name="pro" value="+">+ <input type="radio" name="pro" value="-">-<br>Ketones: <input type="radio" name="ket" value="+">+ <input type="radio" name="ket" value="-">-</td>
                    <td><small>Dipstick Result</small></td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_pro" value="Y"> (+) Protein</label></td>
                </tr>

                <tr style="background:#F3F4F6;">
                    <td><b>4.6 Risk Profile</b><br><small>(Doctors Only)</small></td>
                    <td colspan="2">
                        <div class="radio-inline" style="justify-content:space-between">
                            <label><input type="radio" name="rp" value="5"> <5%</label>
                            <label><input type="radio" name="rp" value="10"> 5-10%</label>
                            <label><input type="radio" name="rp" value="20"> 10-20%</label>
                            <label><input type="radio" name="rp" value="30"> 20-30%</label>
                            <label><input type="radio" name="rp" value="31"> ≥30%</label>
                        </div>
                    </td>
                    <td class="risk-area"><label class="check-group" style="color:#991B1B; font-weight:bold;"><input type="checkbox" name="r_30" value="Y"> ≥30% Risk</label></td>
                </tr>

                <tr>
                    <td><b>4.7 Cancer Screening</b><br><small>(Women 30+ yrs)</small></td>
                    <td colspan="2">
                        <small>Nai-screen na ba sa Breast/Cervical Cancer?</small>
                        <div class="radio-inline">
                            <label><input type="radio" name="cs" value="Y"> Oo (paalalahan)</label>
                            <label><input type="radio" name="cs" value="N"> Hindi (i-refer)</label>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <a href="{{ url()->previous() }}" style="text-decoration:none; padding:10px 20px; color:#4B5563;">Cancel</a>
            <button type="submit" class="btn-save">Finalize DOH Assessment</button>
        </div>
    </form>
</div>
</body>
</html>