<div id="patientDetailModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    
    <div class="modal-content" style="background: white; width: 95%; max-width: 1200px; max-height: 90vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        
        <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h3 style="margin: 0; font-size: 18px; color: #111827;">Patient Record Details</h3>
                <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">
                    <span id="headerPatientID" style="font-weight: 500;">---</span> • 
                    <span id="detInternalId" style="display:none;"></span>
                    <span id="headerPatientName">---</span>
                </p>
            </div>
            <button onclick="closePatientModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #9CA3AF;">&times;</button>
        </div>

        

        <div class="modal-tabs" style="display: flex; border-bottom: 1px solid #E5E7EB; background: #F9FAFB; padding: 0 20px;">
            <div class="tab-item active" onclick="switchTab(event, 'basic-info')">Basic Information</div>
            <div class="tab-item" onclick="switchTab(event, 'medical-history')">Medical History</div>
            <div class="tab-item" onclick="switchTab(event, 'service-history')">Service History</div>
            <div class="tab-item" onclick="switchTab(event, 'medicine-history')">Medicine History</div>
            <div id="tab-immunization" class="tab-item" onclick="switchTab(event, 'immunization')" style="display: none;">Immunization</div>
            <div id="tab-maternal" class="tab-item" onclick="switchTab(event, 'maternal-health')" style="display: none;">Maternal Health</div>
        </div>

        <div class="modal-body" style="padding: 24px; overflow-y: auto; flex: 1; background: #F9FAFB;">
            
            <div id="basic-info" class="tab-pane active">
                <div class="detail-card">
                    <h5>Patient Details</h5>
                    <div class="detail-grid">
                        <div class="item"><span>PATIENT ID</span><p id="detID">---</p></div>
                        <div class="item"><span>FAMILY NUMBER</span><p id="detFam">---</p></div>
                        <div class="item"><span>FULL NAME</span><p id="detFullName">---</p></div>
                        <div class="item"><span>SEX</span><p id="detSex">---</p></div>
                        <div class="item"><span>DATE OF BIRTH</span><p id="detDOB">---</p></div>
                        <div class="item"><span>AGE</span><p id="detAge" style="font-weight: bold; color: #1A73E8;">---</p></div>
                        <div class="item"><span>PLACE OF BIRTH</span><p id="detPOB">---</p></div>
                        <div class="item"><span>CIVIL STATUS</span><p id="detCivil">---</p></div>
                        <div class="item"><span>RELIGION</span><p id="detReligion">---</p></div>
                        <div class="item"><span>EDUCATIONAL ATTAINMENT</span><p id="detEducation">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Family Information</h5>
                    <div class="detail-grid">
                        <div class="item"><span>MOTHER'S NAME</span><p id="detMother">---</p></div>
                        <div class="item"><span>FATHER'S NAME</span><p id="detFather">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Address</h5>
                    <div class="detail-grid">
                        <div class="item"><span>HOUSE NO. / STREET</span><p id="detAddress">---</p></div>
                        <div class="item"><span>BARANGAY</span><p id="detBrgy">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Contact Information</h5>
                    <div class="detail-grid">
                        <div class="item"><span>CONTACT NUMBER</span><p id="detContact">---</p></div>
                        <div class="item"><span>EMAIL ADDRESS</span><p id="detEmail">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Government/Program IDs</h5>
                    <div class="detail-grid">
                        <div class="item"><span>OSCA/PWD NO.</span><p id="detOscaPwd">---</p></div>
                        <div class="item"><span>4PS HOUSEHOLD NO.</span><p id="det4ps">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>PhilHealth Information</h5>
                    <div class="detail-grid">
                        <div class="item"><span>PHILHEALTH TYPE</span><p id="detPhilType">---</p></div>
                        <div class="item"><span>PHILHEALTH NUMBER</span><p id="detPhilNo">---</p></div>
                        <div class="item" id="phMemberNameBox" style="display: none;"><span>MEMBER'S NAME</span><p id="detPhilMemberName">---</p></div>
                        <div class="item" id="phMemberDobBox" style="display: none;"><span>MEMBER'S DOB</span><p id="detPhilMemberDob">---</p></div>
                    </div>
                </div>

            </div>
            

            <div id="medical-history" class="tab-pane" style="display: none;">
                
                <div id="medical-history-empty" style="display: none; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; background: white; border: 1px dashed #D1D5DB; border-radius: 10px;">
                    <span style="font-size: 40px; margin-bottom: 12px;">📝</span>
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">No Medical History Found</h4>
                    <p style="margin: 4px 0 20px; font-size: 13px; color: #6B7280; text-align: center; max-width: 400px;">This patient has not yet undergone their initial Integrated NCD Risk Assessment.</p>
                    @if(auth()->user()->role === 'doctor')
                        <a id="btn-start-ncd" href="#" class="btn-primary" style="text-decoration: none; display: inline-block;">
                            Conduct NCD Risk Assessment
                        </a>
                    @else
                        <p style="font-size: 13px; font-style: italic; color: #9CA3AF; margin-top: 10px;">
                            Please coordinate with the attending Doctor to conduct this assessment.
                        </p>
                    @endif             
                   </div>

                <div id="medical-history-filled" style="display: none;">
                    <div style="margin-bottom: 20px;">
                        <h4 style="margin: 0; font-size: 16px; color: #111827;">Integrated NCD Risk Assessment Form</h4>
                        <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">Department of Health Baseline Profile</p>
                    </div>

                    <div class="detail-card">
                        <h5>Part II. Past Medical History</h5>
                        <div class="detail-grid">
                            <div class="item"><span>KARAMDAMAN (Conditions)</span><p id="doh_conditions">---</p></div>
                            <div class="item"><span>CANCER SITE (If applicable)</span><p id="doh_cancer_site">---</p></div>
                            <div class="item"><span>CHEST PAIN / ANGINA</span><p id="doh_chest_pain">---</p></div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <h5>Part III. Assessment of Risk Factors</h5>
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #F3F4F6;">
                            <span style="font-size: 12px; font-weight: bold; color: #374151;">A. Non-Modifiable (Family History)</span>
                            <div class="detail-grid" style="margin-top: 10px;">
                                <div class="item"><span>1ST DEGREE RELATIVES WITH:</span><p id="doh_fam_history">---</p></div>
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 12px; font-weight: bold; color: #374151;">B. Modifiable Risk Factors</span>
                            <div class="detail-grid" style="margin-top: 10px;">
                                <div class="item"><span>B.1 NUTRITION (Healthy/Unhealthy)</span><p id="doh_nutrition">---</p></div>
                                <div class="item"><span>B.2 ALCOHOL (Binge/Occasional)</span><p id="doh_alcohol">---</p></div>
                                <div class="item"><span>B.3 EXERCISE (Intensity/Minutes)</span><p id="doh_exercise">---</p></div>
                                <div class="item"><span>B.4 SMOKING (Status/Sticks)</span><p id="doh_smoking">---</p></div>
                                <div class="item"><span>B.5 STRESS (Frequency)</span><p id="doh_stress">---</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-card">
                        <h5>Part IV. Risk Screening</h5>
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #F3F4F6;">
                            <span style="font-size: 12px; font-weight: bold; color: #374151;">4.1 Anthropometric Measurement</span>
                            <div class="detail-grid" style="margin-top: 10px;">
                                <div class="item"><span>WEIGHT (kg)</span><p id="doh_weight">---</p></div>
                                <div class="item"><span>HEIGHT (cm)</span><p id="doh_height">---</p></div>
                                <div class="item"><span>BMI (Status)</span><p id="doh_bmi" style="font-weight:bold;">---</p></div>
                                <div class="item"><span>WAIST (cm)</span><p id="doh_waist">---</p></div>
                                <div class="item"><span>W/H RATIO</span><p id="doh_wh_ratio">---</p></div>
                            </div>
                        </div>
                        
                        <div class="detail-grid">
                            <div class="item"><span>4.2 BLOOD SUGAR (FBS/RBS)</span><p id="doh_sugar">---</p></div>
                            <div class="item"><span>4.3 BLOOD PRESSURE (Baseline)</span><p id="doh_bp" style="font-weight:bold; color:#DC2626;">---</p></div>
                            <div class="item"><span>4.4 CHOLESTEROL LEVEL</span><p id="doh_cholesterol">---</p></div>
                            <div class="item"><span>4.5 URINE DIPSTICK (Protein)</span><p id="doh_urine_pro">---</p></div>
                            <div class="item"><span>4.5 URINE DIPSTICK (Ketones)</span><p id="doh_urine_ket">---</p></div>
                            <div class="item"><span>4.7 CANCER SCREENING</span><p id="doh_cancer_screen">---</p></div>
                        </div>
                    </div>
                </div>
            </div>

                

            <div id="service-history" class="tab-pane" style="display: none;">
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">Service History</h4>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">Complete record of all visits and vital signs</p>
                </div>
                <div class="table-card" style="background: white; border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>DATE OF VISIT</th>
                                    <th>BLOOD PRESSURE</th>
                                    <th>WEIGHT</th>
                                    <th>HEIGHT</th>
                                    <th>TEMPERATURE</th>
                                    <th>SERVICE TYPE</th>
                                    <th>REASON FOR VISIT</th>
                                    <th>DIAGNOSIS</th>
                                    <th>ACTION TAKEN</th>
                                    <th>ATTENDING STAFF</th>
                                    <th>NOTES</th>
                                </tr>
                            </thead>
                            <tbody id="detServiceHistoryBody">
                                <tr><td colspan="11" style="text-align: center; padding: 40px; color: #9CA3AF;">No service history recorded for this patient.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="medicine-history" class="tab-pane" style="display: none;">
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">Medicine History</h4>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">All medicines dispensed to this patient</p>
                </div>
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; padding: 15px; border-radius: 8px; margin-bottom: 16px;">
                    <span style="font-size: 14px; color: #1E40AF; font-weight: 500;">Total Medicines Dispensed: <span id="detTotalMedicines">0</span></span>
                </div>
                <div class="table-card" style="background: white; border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>MEDICINE NAME</th>
                                    <th>QUANTITY GIVEN</th>
                                    <th>UNIT</th>
                                    <th>DISPENSED BY</th>
                                </tr>
                            </thead>
                            <tbody id="detMedicineHistoryBody">
                                <tr><td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">No medicine history recorded.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="margin-top: 16px; padding: 12px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 12px; color: #6B7280;">
                    📋 <b>Note:</b> This is a read-only historical record based on inventory dispensing.
                </div>
            </div>

            <div id="immunization" class="tab-pane" style="display: none;">
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">Immunization Record</h4>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">Vaccines administered to this patient</p>
                </div>
                <div class="table-card" style="background: white; border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden;">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>VACCINE</th>
                                    <th>DATE GIVEN</th>
                                    <th>VACCINE</th>
                                    <th>DATE GIVEN</th>
                                    <th>VACCINE</th>
                                    <th>DATE GIVEN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">BCG</td><td id="v_vac_bcg" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PENTA 2</td><td id="v_vac_penta2" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">MMR 1</td><td id="v_vac_mmr1">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">HEPA B</td><td id="v_vac_hepa" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">OPV 2</td><td id="v_vac_opv2" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">MMR 2</td><td id="v_vac_mmr2">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PENTA 1</td><td id="v_vac_penta1" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PCV 2</td><td id="v_vac_pcv2" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">HPV 1</td><td id="v_vac_hpv1">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">OPV 1</td><td id="v_vac_opv1" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PENTA 3</td><td id="v_vac_penta3" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">HPV 2</td><td id="v_vac_hpv2">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PCV 1</td><td id="v_vac_pcv1" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">OPV 3</td><td id="v_vac_opv3" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">FLU</td><td id="v_vac_flu">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">IPV 1</td><td id="v_vac_ipv1" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PCV 3</td><td id="v_vac_pcv3" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">PNEUMONIA</td><td id="v_vac_pneumo">---</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">IPV 2</td><td id="v_vac_ipv2" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">MR 1</td><td id="v_vac_mr1" style="border-right: 1px solid #F1F5F9;">---</td>
                                    <td style="font-weight: bold; border-right: 1px solid #F1F5F9;">TD</td><td id="v_vac_td">---</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="margin-top: 16px; padding: 12px; background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; font-size: 12px; color: #1E40AF; display: flex; align-items: center; gap: 8px;">
                    <span>ℹ️</span>
                    <span><b>Note:</b> Immunization tracking is enabled for this patient. Records are automatically updated when vaccines are administered.</span>
                </div>
            </div>

            <div id="maternal-health" class="tab-pane" style="display: none;">
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">Maternal & Child Health Tracking</h4>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">Birth details, OB history, and supplements</p>
                </div>

                <div class="detail-card">
                    <h5>Newborn & Birth Details</h5>
                    <div class="detail-grid">
                        <div class="item"><span>NEWBORN SCREENING</span><p id="v_mat_nbs">---</p></div>
                        <div class="item"><span>NBS DATE</span><p id="v_mat_nbs_date">---</p></div>
                        <div class="item"><span>NBS RESULT</span><p id="v_mat_nbs_result">---</p></div>
                        <div class="item"><span>HEARING TEST</span><p id="v_mat_hearing">---</p></div>
                        <div class="item"><span>HEARING DATE</span><p id="v_mat_hearing_date">---</p></div>
                        <div class="item"><span>HEARING RESULT</span><p id="v_mat_hearing_result">---</p></div>
                        <div class="item"><span>BIRTH ORDER</span><p id="v_mat_birth_order">---</p></div>
                        <div class="item"><span>BIRTH LENGTH (cm)</span><p id="v_mat_birth_length">---</p></div>
                        <div class="item"><span>BIRTH WEIGHT (kg)</span><p id="v_mat_birth_weight">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Delivery & Feeding</h5>
                    <div class="detail-grid">
                        <div class="item"><span>TYPE OF DELIVERY</span><p id="v_mat_del_type">---</p></div>
                        <div class="item"><span>TYPE OF FEEDING</span><p id="v_mat_feed_type">---</p></div>
                        <div class="item"><span>BIRTH ATTENDANT</span><p id="v_mat_attendant">---</p></div>
                        <div class="item"><span>PLACE OF DELIVERY</span><p id="v_mat_del_place">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Supplements</h5>
                    <div class="detail-grid">
                        <div class="item"><span>VITAMIN A DOSE</span><p id="v_mat_vit_a_dose">---</p></div>
                        <div class="item"><span>VITAMIN A DATE</span><p id="v_mat_vit_a_date">---</p></div>
                        <div class="item"></div>
                        <div class="item"><span>DEWORMING (1ST DOSE)</span><p id="v_mat_deworm1">---</p></div>
                        <div class="item"><span>DEWORMING (2ND DOSE)</span><p id="v_mat_deworm2">---</p></div>
                    </div>
                </div>

                <div class="detail-card">
                    <h5>Obstetric History (OB History)</h5>
                    <div class="detail-grid">
                        <div class="item"><span>GRAVIDA (G)</span><p id="v_ob_g">---</p></div>
                        <div class="item"><span>PARA (P) - TPAL</span><p id="v_ob_p">---</p></div>
                        <div class="item"><span>MENARCHE (AGE)</span><p id="v_ob_menarche">---</p></div>
                        <div class="item"><span>PMP</span><p id="v_ob_pmp">---</p></div>
                        <div class="item"><span>LMP</span><p id="v_ob_lmp">---</p></div>
                        <div class="item"><span>EDC (DUE DATE)</span><p id="v_ob_edc">---</p></div>
                        <div class="item"><span>TT/TD STATUS</span><p id="v_ob_tt_status" style="font-weight:bold; color: #1E40AF;">---</p></div>
                    </div>
                    <div style="margin-top: 15px;">
                        <span style="font-size: 11px; color: #9CA3AF; font-weight: 600; text-transform: uppercase;">TD Doses Administered</span>
                        <div style="margin-top: 8px; border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden;">
                            <table class="data-table">
                                <thead>
                                    <tr><th>TD 1</th><th>TD 2</th><th>TD 3</th><th>TD 4</th><th>TD 5</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="v_ob_td1">---</td><td id="v_ob_td2">---</td><td id="v_ob_td3">---</td><td id="v_ob_td4">---</td><td id="v_ob_td5">---</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid #E5E7EB; background: white; display: flex; justify-content: flex-end; gap: 12px;">
            <button onclick="closePatientModal()" class="btn-secondary">Close</button>
            <div id="dynamic-action-button"></div>
        </div>
    </div>
</div>

<style>
    .modal-backdrop { display: none; } 
    .tab-item { padding: 14px 20px; font-size: 13px; color: #6B7280; cursor: pointer; border-bottom: 2px solid transparent; white-space: nowrap; }
    .tab-item.active { color: #1A73E8; border-bottom-color: #1A73E8; font-weight: bold; }
    .detail-card { background: white; border: 1px solid #E5E7EB; border-radius: 10px; padding: 20px; margin-bottom: 16px; }
    .detail-card h5 { margin: 0 0 16px; font-size: 14px; color: #111827; border-bottom: 1px solid #F3F4F6; padding-bottom: 8px; }
    .detail-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .checkbox-grid label { font-size: 14px; color: #1F2937; display: flex; align-items: center; gap: 8px; }
    .item span { font-size: 11px; color: #9CA3AF; display: block; margin-bottom: 4px; font-weight: 600; text-transform: uppercase; }
    .item p { margin: 0; font-size: 14px; color: #1F2937; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    .data-table th { text-align: left; font-size: 11px; color: #6B7280; padding: 12px; background: #F9FAFB; border-bottom: 1px solid #E5E7EB; text-transform: uppercase; }
    .data-table td { padding: 14px 12px; border-bottom: 1px solid #F1F5F9; font-size: 13px; color: #374151; }
    .status-pill-purple { display: inline-block; background: #F3E8FF; color: #7E22CE; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 500; }
    .status-pill-blue { display: inline-block; background: #DBEAFE; color: #1E40AF; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 500; }
    .btn-secondary { background: white; border: 1px solid #D1D5DB; padding: 8px 16px; border-radius: 8px; cursor: pointer; }
    .btn-primary { background: #1A73E8; color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; }
</style>

<script>
    const currentUserRole = "{{ strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw') }}";

    // --- HELPER: Toggle PhilHealth Fields ---
    window.togglePhilHealthFields = function() {
        const typeEl = document.getElementById('detPhilType');
        if (!typeEl) return;
        
        const type = typeEl.value.toLowerCase();
        const noBox = document.getElementById('detPhilNo')?.closest('.item');
        const nameBox = document.getElementById('phMemberNameBox');
        const dobBox = document.getElementById('phMemberDobBox');

        if (type === 'none') {
            if (noBox) noBox.style.display = 'none';
            if (nameBox) nameBox.style.display = 'none';
            if (dobBox) dobBox.style.display = 'none';
        } else if (type === 'member') {
            if (noBox) noBox.style.display = 'block';
            if (nameBox) nameBox.style.display = 'none';
            if (dobBox) dobBox.style.display = 'none';
        } else if (type === 'dependent') {
            if (noBox) noBox.style.display = 'block';
            if (nameBox) nameBox.style.display = 'block';
            if (dobBox) dobBox.style.display = 'block';
        }
    };

    // --- HELPER: Tab Switching ---
    function switchTab(event, tabId) {
        document.querySelectorAll('.tab-pane').forEach(pane => pane.style.display = 'none');
        document.querySelectorAll('.tab-item').forEach(tab => tab.classList.remove('active'));
        
        const targetPane = document.getElementById(tabId);
        if(targetPane) targetPane.style.display = 'block';

        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        const btnContainer = document.getElementById('dynamic-action-button');
        if (!btnContainer) return; 
        
        btnContainer.innerHTML = ''; 

        const idElement = document.getElementById('detInternalId');
        const currentPatientId = idElement ? idElement.innerText : '';

        let showButton = false;
        let btnText = "Edit Information";
        let btnAction = `editPatient('${currentPatientId}')`;

        if (currentUserRole === 'admin') {
            showButton = true; 
        } else if ((currentUserRole === 'nurse' || currentUserRole === 'bhw') && tabId === 'basic-info') {
            showButton = true; 
        }

        if (showButton && currentPatientId) {
            btnContainer.innerHTML = `<button class="btn-primary" onclick="${btnAction}">${btnText}</button>`;
        }

            // ✅ ADD THIS 3 LINES HERE — LOAD HISTORY WHEN TAB IS CLICKED
        if (tabId === 'medicine-history') {
            const patientPTN = document.getElementById('headerPatientID').innerText.trim();
            loadMedicineHistory(patientPTN);
        }
    }

    function closePatientModal() {
        document.getElementById('patientDetailModal').style.display = 'none';
    }

    // ✅ NEW FUNCTION: Load Medicine History
function loadMedicineHistory(patientPTN) {
    fetch(`/nurse/patient/${patientPTN}/medicine-history`)
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('detMedicineHistoryBody');
        const totalSpan = document.getElementById('detTotalMedicines');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">No medicine history recorded.</td></tr>`;
            totalSpan.textContent = 0;
            return;
        }

        totalSpan.textContent = data.length;

        data.forEach(row => {
            tbody.innerHTML += `
            <tr>
                <td>${row.date}</td>
                <td>${row.medicine_name}</td>
                <td>${row.quantity}</td>
                <td>${row.unit}</td>
                <td>${row.dispensed_by}</td>
            </tr>`;
        });
    })
    .catch(err => {
        document.getElementById('detMedicineHistoryBody').innerHTML = `<tr><td colspan="5" style="text-align:center; padding:20px; color:#dc2626;">Error loading history</td></tr>`;
    });
}

    // --- MAIN: Open Modal & Fetch Data ---
   // --- MAIN: Open Modal & Fetch Data ---
    window.openPatientModal = function(id) {
        const modal = document.getElementById('patientDetailModal');
        modal.style.display = 'flex';

        fetch(`/patients/show/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detInternalId').innerText = data.id;

                const basicTab = document.querySelector('.tab-item:first-child');
                switchTab({ currentTarget: basicTab }, 'basic-info');

                // Header Mapping
                document.getElementById('headerPatientID').innerText = data.patient_id || '---';
                document.getElementById('headerPatientName').innerText = `${data.first_name || ''} ${data.last_name || ''}`;
                document.getElementById('detID').innerText = data.patient_id || '---';
                document.getElementById('detFam').innerText = data.family_number || '---';
                
                // Patient Name
                const fullNameEl = document.getElementById('detFullName');
                fullNameEl.innerText = `${data.first_name || ''} ${data.middle_name || ''} ${data.last_name || ''}`.trim() || '---';
                fullNameEl.dataset.first = data.first_name || '';
                fullNameEl.dataset.middle = data.middle_name || '';
                fullNameEl.dataset.last = data.last_name || '';

                // Mother's Name 
                const motherEl = document.getElementById('detMother');
                motherEl.innerText = `${data.mother_first || ''} ${data.mother_middle || ''} ${data.mother_last || ''}`.trim() || '---';
                motherEl.dataset.first = data.mother_first || '';
                motherEl.dataset.middle = data.mother_middle || '';
                motherEl.dataset.last = data.mother_last || '';

                // Father's Name 
                const fatherEl = document.getElementById('detFather');
                fatherEl.innerText = `${data.father_first || ''} ${data.father_middle || ''} ${data.father_last || ''}`.trim() || '---';
                fatherEl.dataset.first = data.father_first || '';
                fatherEl.dataset.middle = data.father_middle || '';
                fatherEl.dataset.last = data.father_last || '';

                // General Basic Info
                document.getElementById('detSex').innerText = data.gender || '---';
                document.getElementById('detDOB').innerText = data.dob || '---';
                document.getElementById('detAge').innerText = data.age || '---';
                document.getElementById('detPOB').innerText = data.pob || '---';
                document.getElementById('detCivil').innerText = data.civil_status || '---';
                document.getElementById('detAddress').innerText = data.address || '---';
                document.getElementById('detBrgy').innerText = data.barangay || '---';
                document.getElementById('detContact').innerText = data.contact_number || '---'; 
                document.getElementById('detEmail').innerText = data.email || '---';

                // Government IDs
                document.getElementById('detOscaPwd').innerText = data.osca_pwd_no || '---';
                document.getElementById('det4ps').innerText = data.four_ps_no || '---';
                document.getElementById('detReligion').innerText = data.religion || '---';
                document.getElementById('detEducation').innerText = data.educational_attainment || '---';

                // PhilHealth Data
                let phType = 'None';
                let phNo = '---';
                let memberNameBox = document.getElementById('phMemberNameBox');
                let memberDobBox = document.getElementById('phMemberDobBox');

                if (data.philhealth === 'member') { 
                    phType = 'Member'; 
                    phNo = data.philhealth_no_member || '---'; 
                    if(memberNameBox) memberNameBox.style.display = 'none';
                    if(memberDobBox) memberDobBox.style.display = 'none';
                } else if (data.philhealth === 'dependent') { 
                    phType = 'Dependent'; 
                    phNo = data.philhealth_no_dependent || '---'; 
                    if(memberNameBox) memberNameBox.style.display = 'block';
                    if(memberDobBox) memberDobBox.style.display = 'block';
                    document.getElementById('detPhilMemberName').innerText = data.philhealth_member_name || '---';
                    document.getElementById('detPhilMemberDob').innerText = data.philhealth_member_dob || '---';
                } else {
                    if(memberNameBox) memberNameBox.style.display = 'none';
                    if(memberDobBox) memberDobBox.style.display = 'none';
                }
                
                document.getElementById('detPhilType').innerText = phType;
                document.getElementById('detPhilNo').innerText = phNo;

                // --- RESTORED: MEDICAL HISTORY MAPPING ---
                if(document.getElementById('detHyper')) document.getElementById('detHyper').checked = !!data.is_hypertensive;
                if(document.getElementById('detDiab')) document.getElementById('detDiab').checked = !!data.is_diabetic;
                if(document.getElementById('detAsthma')) document.getElementById('detAsthma').checked = !!data.has_asthma;
                if(document.getElementById('detHeart')) document.getElementById('detHeart').checked = !!data.has_heart_disease;
                if(document.getElementById('detTB')) document.getElementById('detTB').checked = !!data.has_tb_history;
                if(document.getElementById('detOtherConditions')) document.getElementById('detOtherConditions').innerText = data.other_conditions || 'None';
                if(document.getElementById('detFoodAllergy')) document.getElementById('detFoodAllergy').innerText = data.food_allergies || 'None';
                if(document.getElementById('detDrugAllergy')) document.getElementById('detDrugAllergy').innerText = data.drug_allergies || 'None';
                if(document.getElementById('detOtherAllergy')) document.getElementById('detOtherAllergy').innerText = data.other_allergies || 'None';
                if(document.getElementById('detIsSenior')) document.getElementById('detIsSenior').innerText = data.age >= 60 ? 'Yes' : 'No';
                if(document.getElementById('detIsPWD')) document.getElementById('detIsPWD').innerText = data.osca_pwd_no ? 'Yes' : 'No';
                if(document.getElementById('detFamHyper')) document.getElementById('detFamHyper').checked = !!data.fam_hypertension;
                if(document.getElementById('detFamDiab')) document.getElementById('detFamDiab').checked = !!data.fam_diabetes;
                if(document.getElementById('detFamOther')) document.getElementById('detFamOther').innerText = data.fam_other || 'None';

                // --- RESTORED: IMMUNIZATION MAPPING ---
                document.getElementById('v_vac_bcg').innerText = data.vac_bcg || '---';
                document.getElementById('v_vac_hepa').innerText = data.vac_hepa || '---';
                document.getElementById('v_vac_penta1').innerText = data.vac_penta1 || '---';
                document.getElementById('v_vac_opv1').innerText = data.vac_opv1 || '---';
                document.getElementById('v_vac_pcv1').innerText = data.vac_pcv1 || '---';
                document.getElementById('v_vac_ipv1').innerText = data.vac_ipv1 || '---';
                document.getElementById('v_vac_ipv2').innerText = data.vac_ipv2 || '---';
                document.getElementById('v_vac_penta2').innerText = data.vac_penta2 || '---';
                document.getElementById('v_vac_opv2').innerText = data.vac_opv2 || '---';
                document.getElementById('v_vac_pcv2').innerText = data.vac_pcv2 || '---';
                document.getElementById('v_vac_penta3').innerText = data.vac_penta3 || '---';
                document.getElementById('v_vac_opv3').innerText = data.vac_opv3 || '---';
                document.getElementById('v_vac_pcv3').innerText = data.vac_pcv3 || '---';
                document.getElementById('v_vac_mr1').innerText = data.vac_mr1 || '---';
                document.getElementById('v_vac_mmr1').innerText = data.vac_mmr1 || '---';
                document.getElementById('v_vac_mmr2').innerText = data.vac_mmr2 || '---';
                document.getElementById('v_vac_hpv1').innerText = data.vac_hpv1 || '---';
                document.getElementById('v_vac_hpv2').innerText = data.vac_hpv2 || '---';
                document.getElementById('v_vac_flu').innerText = data.vac_flu || '---';
                document.getElementById('v_vac_pneumo').innerText = data.vac_pneumo || '---';
                document.getElementById('v_vac_td').innerText = data.vac_td || '---';

                // --- RESTORED: MATERNAL HEALTH MAPPING ---
                document.getElementById('v_mat_nbs').innerText = data.mat_nbs || '---';
                document.getElementById('v_mat_nbs_date').innerText = data.mat_nbs_date || '---';
                document.getElementById('v_mat_nbs_result').innerText = data.mat_nbs_result || '---';
                document.getElementById('v_mat_hearing').innerText = data.mat_hearing || '---';
                document.getElementById('v_mat_hearing_date').innerText = data.mat_hearing_date || '---';
                document.getElementById('v_mat_hearing_result').innerText = data.mat_hearing_result || '---';
                document.getElementById('v_mat_birth_order').innerText = data.mat_birth_order || '---';
                document.getElementById('v_mat_birth_length').innerText = data.mat_birth_length ? data.mat_birth_length + ' cm' : '---';
                document.getElementById('v_mat_birth_weight').innerText = data.mat_birth_weight ? data.mat_birth_weight + ' kg' : '---';
                
                document.getElementById('v_mat_del_type').innerText = data.mat_delivery_type || '---';
                document.getElementById('v_mat_feed_type').innerText = data.mat_feeding_type || '---';
                document.getElementById('v_mat_attendant').innerText = data.mat_attendant || '---';
                document.getElementById('v_mat_del_place').innerText = data.mat_delivery_place || '---';

                document.getElementById('v_mat_vit_a_dose').innerText = data.mat_vit_a_dose || '---';
                document.getElementById('v_mat_vit_a_date').innerText = data.mat_vit_a_date || '---';
                document.getElementById('v_mat_deworm1').innerText = data.mat_deworming_1 || '---';
                document.getElementById('v_mat_deworm2').innerText = data.mat_deworming_2 || '---';

                document.getElementById('v_ob_g').innerText = data.ob_g || '---';
                let tpal = `${data.ob_p_t || 0}-${data.ob_p_p || 0}-${data.ob_p_a || 0}-${data.ob_p_l || 0}`;
                document.getElementById('v_ob_p').innerText = tpal === '0-0-0-0' ? '---' : tpal;
                document.getElementById('v_ob_menarche').innerText = data.ob_menarche || '---';
                document.getElementById('v_ob_pmp').innerText = data.ob_pmp || '---';
                document.getElementById('v_ob_lmp').innerText = data.ob_lmp || '---';
                document.getElementById('v_ob_edc').innerText = data.ob_edc || '---';
                document.getElementById('v_ob_tt_status').innerText = data.ob_tt_status || '---';
                
                document.getElementById('v_ob_td1').innerText = data.ob_td1 || '---';
                document.getElementById('v_ob_td2').innerText = data.ob_td2 || '---';
                document.getElementById('v_ob_td3').innerText = data.ob_td3 || '---';
                document.getElementById('v_ob_td4').innerText = data.ob_td4 || '---';
                document.getElementById('v_ob_td5').innerText = data.ob_td5 || '---';

                // Tab Visibility (Restored)
                const tabImmun = document.getElementById('tab-immunization');
                const tabMat = document.getElementById('tab-maternal');
                if (tabImmun) tabImmun.style.display = (data.tracking_immunization === 'Yes') ? 'block' : 'none';
                if (tabMat) tabMat.style.display = (data.tracking_maternal === 'Yes') ? 'block' : 'none';
                // --- DOH NCD RISK ASSESSMENT LOGIC ---
                // We check if a baseline vital sign like Blood Pressure or BMI exists. 
                // If it's missing, it means they haven't taken the assessment yet.
                if (!data.screen_bp && !data.screen_bmi) {
                    
                    // Show Empty State Button
                    document.getElementById('medical-history-empty').style.display = 'flex';
                    document.getElementById('medical-history-filled').style.display = 'none';
                    
                    // Set the button to redirect to your dedicated Assessment Form page
                    document.getElementById('btn-start-ncd').href = `/patients/${data.id}/ncd-assessment`;

                } else {
                    
                    // Show Filled DOH Data
                    document.getElementById('medical-history-empty').style.display = 'none';
                    document.getElementById('medical-history-filled').style.display = 'block';

                    // Map Part II
                    let conditions = [];
                    if(data.is_diabetic) conditions.push('Diabetes');
                    if(data.is_hypertensive) conditions.push('Hypertension');
                    if(data.has_cancer) conditions.push('Cancer');
                    if(data.has_copd) conditions.push('COPD');
                    if(data.has_eye_disease) conditions.push('Eye Disease');
                    document.getElementById('doh_conditions').innerText = conditions.length > 0 ? conditions.join(', ') : 'None';
                    document.getElementById('doh_cancer_site').innerText = data.cancer_site || '---';
                    document.getElementById('doh_chest_pain').innerText = data.has_chest_pain ? 'Yes (Angina)' : 'No';

                    // Map Part III
                    let fam = [];
                    if(data.fam_hypertension) fam.push('Hypertension');
                    if(data.fam_diabetes) fam.push('Diabetes');
                    if(data.fam_stroke) fam.push('Stroke');
                    if(data.fam_cancer) fam.push('Cancer');
                    if(data.fam_kidney_disease) fam.push('Kidney Disease');
                    document.getElementById('doh_fam_history').innerText = fam.length > 0 ? fam.join(', ') : 'None';
                    
                    document.getElementById('doh_nutrition').innerText = data.risk_nutrition || '---';
                    document.getElementById('doh_alcohol').innerText = data.risk_alcohol || '---';
                    document.getElementById('doh_exercise').innerText = data.risk_activity || '---';
                    document.getElementById('doh_smoking').innerText = data.risk_smoking || '---';
                    document.getElementById('doh_stress').innerText = data.risk_stress || '---';

                    // Map Part IV
                    document.getElementById('doh_weight').innerText = data.screen_weight || '---';
                    document.getElementById('doh_height').innerText = data.screen_height || '---';
                    document.getElementById('doh_bmi').innerText = data.screen_bmi || '---';
                    document.getElementById('doh_waist').innerText = data.screen_waist || '---';
                    document.getElementById('doh_wh_ratio').innerText = data.screen_wh_ratio || '---';
                    document.getElementById('doh_sugar').innerText = data.screen_sugar || '---';
                    document.getElementById('doh_bp').innerText = data.screen_bp || '---';
                    document.getElementById('doh_cholesterol').innerText = data.screen_cholesterol || '---';
                    document.getElementById('doh_urine_pro').innerText = data.screen_urine_protein || '---';
                    document.getElementById('doh_urine_ket').innerText = data.screen_urine_ketones || '---';
                    document.getElementById('doh_cancer_screen').innerText = data.cancer_screen || '---';
                }
            })
            .catch(error => console.error('Error loading patient:', error));
    };

    // --- MAIN: Edit Mode UI Generation ---
    window.editPatient = function(patientId) {
        if (!patientId) patientId = document.getElementById('detInternalId').innerText;

        Swal.fire({
            title: 'Enable Editing?',
            text: "Switching to edit mode for Basic Information.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Edit'
        }).then((result) => {
            if (result.isConfirmed) {
                const paragraphs = document.querySelectorAll('#basic-info .item p');
                paragraphs.forEach(p => {
                    // Fields that should never be edited
                    if (['detID', 'detAge', 'detFam'].includes(p.id)) return;

                    const currentValue = p.innerText === '---' ? '' : p.innerText;
                    
                    // --- Handle Names (3 inputs) ---
                    if (['detFullName', 'detMother', 'detFather'].includes(p.id)) {
                        const container = document.createElement('div');
                        container.style.display = "grid";
                        container.style.gridTemplateColumns = "1fr 1fr 1fr";
                        container.style.gap = "5px";
                        
                        let prefix = 'edit_';
                        if (p.id === 'detMother') prefix = 'mother_';
                        if (p.id === 'detFather') prefix = 'father_';

                        const names = [
                            { id: prefix + 'first_name', val: p.dataset.first, placeholder: 'First Name' },
                            { id: prefix + 'middle_name', val: p.dataset.middle, placeholder: 'Middle Name' },
                            { id: prefix + 'last_name', val: p.dataset.last, placeholder: 'Last Name' }
                        ];

                        names.forEach(n => {
                            const nInput = document.createElement('input');
                            nInput.type = 'text';
                            nInput.id = n.id;
                            nInput.value = n.val; 
                            nInput.placeholder = n.placeholder;
                            nInput.style.cssText = "width:100%; padding:8px; border:1px solid #1A73E8; border-radius:4px; font-size:12px;";
                            container.appendChild(nInput);
                        });
                        
                        p.replaceWith(container);
                        return; // Skip rest of loop
                    }

                    let input;
                    
                    // --- Handle Dropdowns ---
                    if (['detSex', 'detBrgy', 'detCivil', 'detPhilType'].includes(p.id)) {
                        input = document.createElement('select');
                        let options = [];
                        if (p.id === 'detSex') options = ['Male', 'Female'];
                        if (p.id === 'detBrgy') options = ['Barangay 91', 'Barangay 92', 'Barangay 93', 'Barangay 94', 'Barangay 95', 'Barangay 97', 'Barangay 103', 'Barangay 104'];
                        if (p.id === 'detCivil') options = ['Single', 'Married', 'Widowed', 'Separated'];
                        
                        if (p.id === 'detPhilType') {
                            options = ['None', 'Member', 'Dependent'];
                            const capVal = currentValue.charAt(0).toUpperCase() + currentValue.slice(1).toLowerCase();
                            options.forEach(opt => {
                                const option = new Option(opt, opt);
                                if(opt === capVal) option.selected = true;
                                input.add(option);
                            });
                            input.onchange = window.togglePhilHealthFields; // Trigger toggle on change
                        } else {
                            options.forEach(opt => {
                                const option = new Option(opt, opt);
                                if(opt === currentValue) option.selected = true;
                                input.add(option);
                            });
                        }
                    } 
                    // --- Handle Regular Text / Dates / Numbers ---
                    else {
                        input = document.createElement('input');
                        input.type = (p.id === 'detDOB' || p.id === 'detPhilMemberDob') ? 'date' : 'text';
                        input.value = currentValue;
                        
                        // Strict Date Logic
                        if (p.id === 'detDOB' || p.id === 'detPhilMemberDob') {
                            input.max = new Date().toISOString().split('T')[0];
                        }
                        
                        // Strict 11-digit Contact Logic
                        if (p.id === 'detContact') {
                            input.maxLength = 11;
                            input.placeholder = "09XXXXXXXXX";
                            input.oninput = function() { this.value = this.value.replace(/[^0-9]/g, ''); };
                        }
                        
                        // Strict 12-digit PhilHealth Logic
                        if (p.id === 'detPhilNo') {
                            input.maxLength = 12; 
                            input.placeholder = "12-digit PhilHealth No";
                            input.oninput = function() { this.value = this.value.replace(/[^0-9]/g, ''); };
                        }
                    }

                    input.id = p.id;
                    input.style.cssText = "width:100%; padding:8px; border:1px solid #1A73E8; border-radius:4px;";
                    p.replaceWith(input);
                });

                // Immediately toggle PhilHealth boxes to match the current selected option
                window.togglePhilHealthFields();

                // Swap buttons
                document.getElementById('dynamic-action-button').innerHTML = 
                    `<button class="btn-primary" onclick="savePatientUpdate('${patientId}')">Save Changes</button>
                     <button class="btn-secondary" style="margin-left:8px;" onclick="location.reload()">Cancel</button>`;
            }
        });
    };

    // --- MAIN: Submit Edit Data ---
    window.savePatientUpdate = function(dbId) {
        const contactNo = document.getElementById('detContact')?.value || '';
        
        // Final Validations
        if (document.getElementById('edit_first_name')?.value === '' || document.getElementById('edit_last_name')?.value === '') {
            Swal.fire('Warning', 'Patient First Name and Last Name cannot be empty.', 'warning');
            return;
        }
        if (contactNo.length > 0 && contactNo.length !== 11) {
            Swal.fire('Warning', 'Contact Number must be exactly 11 digits.', 'warning');
            return;
        }

        // Parse Philhealth Dropdown
        const philType = document.getElementById('detPhilType')?.value.toLowerCase() || 'none';
        const philNo = document.getElementById('detPhilNo')?.value || '';

        // Build Payload
        const updatedData = {
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}",
            _method: "PUT",
            
            first_name: document.getElementById('edit_first_name')?.value || '',
            middle_name: document.getElementById('edit_middle_name')?.value || '',
            last_name: document.getElementById('edit_last_name')?.value || '',
            
            mother_first: document.getElementById('mother_first_name')?.value || '',
            mother_middle: document.getElementById('mother_middle_name')?.value || '',
            mother_last: document.getElementById('mother_last_name')?.value || '',
            
            father_first: document.getElementById('father_first_name')?.value || '',
            father_middle: document.getElementById('father_middle_name')?.value || '',
            father_last: document.getElementById('father_last_name')?.value || '',

            dob: document.getElementById('detDOB')?.value || '',
            pob: document.getElementById('detPOB')?.value || '',
            gender: document.getElementById('detSex')?.value || '',
            civil_status: document.getElementById('detCivil')?.value || '',
            address: document.getElementById('detAddress')?.value || '',
            barangay: document.getElementById('detBrgy')?.value || '',
            contact_no: contactNo,
            email: document.getElementById('detEmail')?.value || '',

            osca_pwd_no: document.getElementById('detOscaPwd')?.value || '',
            four_ps_no: document.getElementById('det4ps')?.value || '',
            religion: document.getElementById('detReligion')?.value || '',
            educational_attainment: document.getElementById('detEducation')?.value || '',

            philhealth: philType,
            philhealth_no_member: philType === 'member' ? philNo : '',
            philhealth_no_dependent: philType === 'dependent' ? philNo : '', // Matching DB column
            philhealth_member_name: philType === 'dependent' ? document.getElementById('detPhilMemberName')?.value : '',
            philhealth_member_dob: philType === 'dependent' ? document.getElementById('detPhilMemberDob')?.value : ''
        };

        Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        fetch(`${window.location.origin}/patients/${dbId}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': updatedData._token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(updatedData)
        })
        .then(async response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("text/html") !== -1) {
                throw new Error("Server returned HTML.");
            }
            if (!response.ok) throw new Error("Server error - check required fields");
            return response.json();
        })
        .then(result => {
            Swal.fire('Updated!', 'Patient record saved successfully.', 'success').then(() => location.reload());
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Update failed. Check your network or required fields.', 'error');
        });
    };
</script>