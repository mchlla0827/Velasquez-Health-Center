<div id="patientDetailModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    
    <div class="modal-content" style="background: white; width: 95%; max-width: 1200px; max-height: 90vh; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        
        <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h3 style="margin: 0; font-size: 18px; color: #111827;">Patient Record Details</h3>
                <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">
                    <span id="headerPatientID" style="font-weight: 500;">---</span> - 
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
                        <div class="item"><span>PROOF OF RESIDENCY / RESIDENCY ID</span><p><button type="button" class="btn-secondary" style="padding:5px 12px; font-size:12px;" onclick="showResidencyProof()">Show Proof of Residency</button></p></div>
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
                    <span style="font-size: 40px; margin-bottom: 12px;">[No Records]</span>
                    <h4 style="margin: 0; font-size: 16px; color: #111827;">No Medical History Found</h4>
                    <p style="margin: 4px 0 20px; font-size: 13px; color: #6B7280; text-align: center; max-width: 400px;">This patient has not yet undergone their initial Integrated NCD Risk Assessment.</p>
                    @if(strtolower(auth()->user()->role ?? '') === 'doctor' || auth()->user()->is_physician_in_charge)
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
    <div id="mh-render"></div>

    <div class="ch-wrap">
        <div class="ch-toolbar">
            <h4 class="ch-title" style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">Consultation history</h4>
            <a class="ch-new-btn" id="chNewConsultationBtn" href="#">+ New consultation</a>
        </div>

        <div id="ch-empty" class="ch-empty" style="display: none;">
            <p>No consultation records yet.</p>
            <a class="ch-new-btn" id="chNewConsultationBtnEmpty" href="#">+ New consultation</a>
        </div>

        <div id="ch-list" class="mhx-ch-list" style="display: none;"></div>
    </div>
</div>
            </div>

                

            <div id="service-history" class="tab-pane" style="display: none;">

    <div style="margin-bottom: 20px;">
        <h4 style="margin: 0; font-size: 16px; color: #111827;">
            Service History
        </h4>

        <p style="margin: 4px 0 0; font-size: 13px; color: #6B7280;">
            Previous visits, assessments, and services received
        </p>
    </div>

    <div id="detServiceHistoryBody">

        <div id="serviceHistoryLoading"
             style="background: white;
                    border: 1px solid #E5E7EB;
                    border-radius: 10px;
                    padding: 40px;
                    text-align: center;
                    color: #9CA3AF;">
            Loading service history...
        </div>

        <div id="serviceHistoryEmpty"
             style="display: none;
                    background: white;
                    border: 1px dashed #D1D5DB;
                    border-radius: 10px;
                    padding: 50px 20px;
                    text-align: center;">

            <div style="font-size: 36px; margin-bottom: 10px;">[No Records]</div>

            <h4 style="margin: 0 0 5px; font-size: 15px; color: #111827;">
                No Service History
            </h4>

            <p style="margin: 0; font-size: 13px; color: #9CA3AF;">
                This patient has no recorded visits or services yet.
            </p>

        </div>

        <div id="serviceHistoryList"></div>

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
<b>Note:</b> This is a read-only historical record based on inventory dispensing.
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

<!-- ========== PROOF OF RESIDENCY VIEWER MODAL ========== -->
<div id="residencyProofModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,.6); z-index: 100001; align-items: center; justify-content: center;">
    <div style="background: #fff; border-radius: 12px; max-width: 90vw; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">
        <div style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; font-size: 15px; color: #111827;">Proof of Residency</h3>
            <button type="button" onclick="closeResidencyProof()" style="background:none; border:none; font-size: 22px; cursor: pointer; color:#9CA3AF;">&times;</button>
        </div>
        <div style="padding: 16px; overflow: auto; display: flex; align-items: center; justify-content: center;">
            <img id="residencyProofImg" src="" style="max-width: 80vw; max-height: 70vh; display: none; border-radius: 6px;">
            <p id="residencyProofEmpty" style="display:none; color:#6B7280; font-size: 13px; padding: 30px;">No proof of residency has been uploaded for this patient.</p>
        </div>
    </div>
</div>

<style>
/* ===== Medical History (NCD) - clean record layout v2 ===== */
.mh-topbar { display: flex; justify-content: flex-end; margin-bottom: -6px; }
.mh-view-btn {
    flex-shrink: 0;
    display: inline-flex; align-items: center; gap: 6px;
    background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE;
    font-size: 12.5px; font-weight: 600;
    padding: 7px 14px; border-radius: 7px;
    text-decoration: none; transition: background .12s;
}
.mh-view-btn:hover { background: #DBEAFE; }
.mh-view-btn svg { width: 13px; height: 13px; }
#mh-render {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    font-size: 13.5px;
    color: #1F2937;
    line-height: 1.5;
}

/* Top summary strip - simple row, not boxed grid */
.mh-meta {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
    padding: 14px 0 18px;
    border-bottom: 1px solid #E5E7EB;
    margin-bottom: 16px;
}
.mh-meta-fields { display: flex; flex-wrap: wrap; gap: 24px; }
.mh-meta-item { min-width: 130px; }
.mh-meta-item .k { font-size: 10.5px; font-weight: 600; letter-spacing: .3px; text-transform: uppercase; color: #9CA3AF; margin-bottom: 3px; }
.mh-meta-item .val { font-size: 14px; font-weight: 600; color: #111827; }

/* Risk flags */
.mh-flags { margin-bottom: 22px; }
.mh-flags-title { font-size: 11px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase; color: #6B7280; margin-bottom: 10px; }
.mh-flag-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
.mh-flag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #FEF2F2; color: #B91C1C;
    font-size: 12px; font-weight: 600;
    padding: 5px 12px; border-radius: 6px;
    border: 1px solid #FECACA;
}
.mh-flag::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #DC2626; flex-shrink: 0; }
.mh-flag.none { background: #F0FDF4; color: #166534; border-color: #BBF7D0; }
.mh-flag.none::before { background: #16A34A; }

/* Accordion sections */
.mh-sec { border: 1px solid #E5E7EB; border-radius: 10px; margin-bottom: 10px; background: #fff; overflow: hidden; }
.mh-sec > summary {
    list-style: none; cursor: pointer;
    padding: 14px 16px;
    font-size: 13.5px; font-weight: 700; color: #111827;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    transition: background .12s;
}
.mh-sec > summary::-webkit-details-marker { display: none; }
.mh-sec > summary:hover { background: #FAFAFA; }
.mh-sec-right { display: flex; align-items: center; gap: 10px; }
.mh-sec-note { font-size: 12px; font-weight: 500; color: #9CA3AF; white-space: nowrap; }
.mh-chevron { width: 9px; height: 9px; border-right: 2px solid #9CA3AF; border-bottom: 2px solid #9CA3AF;
    transform: rotate(-45deg); transition: transform .15s; flex-shrink: 0; }
.mh-sec[open] .mh-chevron { transform: rotate(45deg); }
.mh-body { padding: 4px 18px 18px; border-top: 1px solid #F3F4F6; }

/* Label/value row list - replaces cramped grid boxes */
.mh-rows { }
.mh-row {
    display: flex; align-items: baseline; gap: 16px;
    padding: 9px 0; border-bottom: 1px solid #F3F4F6;
}
.mh-row:last-child { border-bottom: none; }
.mh-row .k { flex: 0 0 220px; font-size: 12.5px; color: #6B7280; }
.mh-row .val { flex: 1; font-size: 13.5px; color: #111827; font-weight: 500; }
.mh-row .val.strong { font-weight: 700; }
.mh-row .val.muted { color: #9CA3AF; font-weight: 400; }

.mh-cols2 .mh-rows { columns: 2; column-gap: 28px; }
.mh-cols2 .mh-row { break-inside: avoid; }

.mh-sub { font-size: 11px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase;
    color: #2563EB; margin: 18px 0 4px; }
.mh-sub:first-child { margin-top: 6px; }

/* Chest pain table */
table.mh-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 4px; }
table.mh-table td { padding: 9px 4px; border-bottom: 1px solid #F3F4F6; }
table.mh-table tr:last-child td { border-bottom: none; }
table.mh-table td:first-child { color: #374151; }
table.mh-table td:last-child { width: 60px; text-align: right; font-weight: 700; }
.mh-yes { color: #DC2626; }
.mh-no { color: #9CA3AF; font-weight: 400; }
.mh-tag { display: inline-block; background: #FEE2E2; color: #B91C1C; font-size: 10.5px; font-weight: 700; padding: 2px 8px; border-radius: 4px; }

.mh-warn {
    display: flex; align-items: flex-start; gap: 9px;
    background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;
    font-size: 12.5px; font-weight: 500; line-height: 1.4;
    padding: 11px 14px; border-radius: 8px; margin-top: 14px;
}
.mh-warn::before { content: '!'; flex-shrink: 0; width: 18px; height: 18px; border-radius: 50%;
    background: #DC2626; color: #fff; font-weight: 800; font-size: 12px;
    display: flex; align-items: center; justify-content: center; }

.mh-chiplist { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 2px; }
.mh-chip { background: #F3F4F6; color: #374151; border-radius: 6px; font-size: 12.5px; padding: 5px 11px; }

@media (max-width: 780px) {
    .mh-cols2 .mh-rows { columns: 1; }
    .mh-row .k { flex-basis: 160px; }
}


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

    #serviceHistoryList {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.service-history-card {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    overflow: hidden;
}

.service-history-header {
    display: flex;
        align-items: flex-start;
    padding: 16px 18px;
    border-bottom: 1px solid #F3F4F6;
}

.service-history-date {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
}

.service-history-time {
    font-size: 11px;
    color: #9CA3AF;
    margin-top: 3px;
}

.service-history-type {
    margin-top: 5px;
    font-size: 14px;
    font-weight: 600;
    color: #1A73E8;
}

.service-history-status {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 9px;
    border-radius: 20px;
    background: #F3F4F6;
    color: #374151;
}

.service-history-body {
    padding: 16px 18px;
}

.service-history-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.service-history-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.service-history-label {
    font-size: 10px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
}

.service-history-value {
    font-size: 13px;
    color: #374151;
}

.service-history-vitals {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-top: 15px;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.service-history-vital {
    text-align: center;
}

.service-history-vital-label {
    display: block;
    font-size: 9px;
    font-weight: 600;
    color: #9CA3AF;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.service-history-vital-value {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
}

.service-history-symptoms {
    margin-top: 15px;
    padding-top: 14px;
    border-top: 1px solid #F3F4F6;
}

.service-history-symptoms p {
    margin: 5px 0 0;
    font-size: 12px;
    line-height: 1.5;
    color: #4B5563;
}

@media (max-width: 700px) {
    .service-history-grid {
        grid-template-columns: 1fr;
    }

    .service-history-vitals {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* ===== Consultation History Timeline (Medical History tab, Phase 2) ===== */
.ch-wrap { margin-top: 24px; }
.ch-header { margin-bottom: 4px; }
.ch-title { font-size: 15px; font-weight: 700; color: #111827; }
.ch-desc { font-size: 12.5px; color: #6B7280; margin-top: 2px; }

.ch-toolbar {
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 10px; margin: 14px 0 12px;
}
.ch-new-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: #2563EB; color: #fff; border: none;
    font-size: 12.5px; font-weight: 600;
    padding: 8px 16px; border-radius: 7px;
    text-decoration: none; cursor: pointer;
}
.ch-new-btn:hover { background: #1D4ED8; }

.ch-filters {
    display: flex; flex-wrap: wrap; gap: 8px;
    background: #F8FAFC; border: 1px solid #E5E7EB; border-radius: 8px;
    padding: 10px 12px; margin-bottom: 14px;
}
.ch-filters input[type="text"], .ch-filters select, .ch-filters input[type="date"] {
    font-size: 12.5px; padding: 6px 9px; border: 1px solid #D1D5DB; border-radius: 6px;
    background: #fff; color: #111827; font-family: inherit;
}
.ch-filters input[type="text"] { flex: 1 1 180px; min-width: 140px; }
.ch-clear-btn {
    font-size: 12px; font-weight: 600; color: #6B7280;
    background: #fff; border: 1px solid #D1D5DB; border-radius: 6px;
    padding: 6px 12px; cursor: pointer;
}
.ch-clear-btn:hover { background: #F3F4F6; }

.ch-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 40px 20px; background: #F8FAFC; border: 1px dashed #D1D5DB; border-radius: 10px;
    text-align: center;
}
.ch-empty p { font-size: 13px; color: #6B7280; margin: 0 0 12px; }

.ch-list { display: flex; flex-direction: column; gap: 10px; }
.ch-card { border: 1px solid #E5E7EB; border-radius: 10px; padding: 14px 16px; background: #fff; }
.ch-card-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; }
.ch-card-date { font-size: 11.5px; font-weight: 600; color: #6B7280; }
.ch-card-type {
    display: inline-block; margin-left: 8px;
    font-size: 11px; font-weight: 700; color: #1D4ED8;
    background: #EFF6FF; padding: 2px 8px; border-radius: 4px;
}
.ch-card-provider { font-size: 13px; font-weight: 600; color: #111827; margin-top: 6px; }
.ch-card-summary { font-size: 12.5px; color: #374151; margin-top: 4px; }
.ch-card-status { font-size: 12px; font-weight: 600; color: #B91C1C; margin-top: 4px; }
.ch-view-btn {
    font-size: 12px; font-weight: 600; color: #2563EB;
    background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px;
    padding: 5px 12px; cursor: pointer; white-space: nowrap;
    text-decoration: none;
}
.ch-view-btn:hover { background: #DBEAFE; }

@media (max-width: 700px) {
    .ch-filters { flex-direction: column; align-items: stretch; }
    .ch-card-head { flex-direction: column; }
}

/* ===== Compact NCD Summary + Consultation List (light redesign) ===== */
/* ===== 5-Section NCD Summary Layout ===== */
.mhx-summary-container {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.mhx-summary-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.mhx-summary-heading {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.mhx-summary-subheading {
    font-size: 12px;
    color: #6B7280;
    margin-top: 3px;
}

.mhx-pill-badge {
    font-size: 11px;
    font-weight: 600;
    color: #DC2626;
    background: #FEF2F2;
    border: 1px solid #FEE2E2;
    padding: 3px 10px;
    border-radius: 9999px;
    white-space: nowrap;
}
.mhx-pill-badge.none {
    color: #16A34A;
    background: #F0FDF4;
    border-color: #DCFCE7;
}

/* Sections stack */
.mhx-section-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.mhx-section-item {
    background: #F9FAFB;
    border: 1px solid #F3F4F6;
    border-radius: 8px;
    padding: 12px 14px;
}

.mhx-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: #6B7280;
    margin-bottom: 6px;
}

.mhx-section-body {
    font-size: 13px;
    color: #1F2937;
}

.mhx-metric-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.mhx-metric-grid span b {
    color: #4B5563;
    font-weight: 600;
    margin-right: 4px;
}

.mhx-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.mhx-tag-item {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 500;
    background: #EFF6FF;
    color: #1D4ED8;
    border: 1px solid #DBEAFE;
    padding: 2px 8px;
    border-radius: 4px;
}
.mhx-tag-item.alert {
    background: #FEF2F2;
    color: #B91C1C;
    border-color: #FECACA;
}

.mhx-summary-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    box-sizing: border-box;
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    padding: 9px;
    font-size: 12.5px;
    font-weight: 600;
    color: #374151;
    text-decoration: none;
    transition: background 0.12s;
}
.mhx-summary-btn:hover {
    background: #F3F4F6;
    color: #111827;
}

/* ===== Consultation Records list styling (was missing) ===== */
.mhx-ch-list { display: flex; flex-direction: column; gap: 10px; }
.mhx-ch-item { border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden; background: #fff; }
.mhx-ch-summary { display: flex; justify-content: space-between; align-items: center; padding: 16px 18px; }
.mhx-ch-left .date-type { font-size: 14.5px; font-weight: 700; color: #111827; }
.mhx-ch-left .date-type .type-tag { color: #2563EB; font-weight: 600; }
.mhx-ch-left .provider-note { font-size: 13px; color: #6B7280; margin-top: 4px; }
.mhx-ch-view-link {
    flex-shrink: 0; margin-left: 14px;
    font-size: 12.5px; font-weight: 600; color: #2563EB;
    background: #EFF6FF; border: 1px solid #DBEAFE; border-radius: 6px;
    padding: 7px 14px; text-decoration: none; white-space: nowrap;
}
.mhx-ch-view-link:hover { background: #DBEAFE; }

.mhx-disabled-btn { opacity: 0.5; cursor: not-allowed !important; }

/* ===== Lightweight blocked-action toast (no external library needed) ===== */
.mhx-toast {
    position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(20px);
    background: #111827; color: #fff; font-size: 13px; font-weight: 500;
    padding: 12px 20px; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    z-index: 99999; opacity: 0; transition: opacity .2s ease, transform .2s ease;
    max-width: 380px; text-align: center; pointer-events: none;
}
.mhx-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>

<script>
    const currentUserRole = "{{ strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw') }}";
    let activePatientData = null; // Store patient data globally for modal use

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

    // FIXED TAB SWITCH LOAD

        if (tabId === 'service-history' && activePatientData) {
    loadServiceHistory(activePatientData.id);
}

        if (tabId === 'medicine-history' && activePatientData) {
            const identifier = activePatientData.patient_id || activePatientData.id;
            loadMedicineHistory(identifier);
        }
    }

    function closePatientModal() {
        document.getElementById('patientDetailModal').style.display = 'none';
        activePatientData = null;
    }

    // --- LOAD MEDICINE HISTORY ---
    function loadMedicineHistory(patientIdentifier) {
        const tbody = document.getElementById('detMedicineHistoryBody');
        const totalSpan = document.getElementById('detTotalMedicines');

        if (!tbody) return;

        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 20px; color: #6B7280;">Loading history...</td></tr>`;


        console.log("Patient Identifier:", patientIdentifier);

        // Root relative URL fetch
        fetch(`/patient/${encodeURIComponent(patientIdentifier)}/medicine-history`)
            .then(async res => {
                if (!res.ok) {
                    const errText = await res.text();
                    throw new Error(`HTTP ${res.status}: ${errText}`);
                }
                return res.json();
            })
            .then(data => {
                tbody.innerHTML = '';

                if (!data || data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 40px; color: #9CA3AF;">No medicine history recorded.</td></tr>`;
                    if (totalSpan) totalSpan.textContent = '0';
                    return;
                }

                if (totalSpan) totalSpan.textContent = data.length;

                let rows = '';
                data.forEach(row => {
                    rows += `
                    <tr>
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">${row.date || '---'}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; font-weight: 500; color: #111827;">${row.medicine_name || '---'}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">${row.quantity}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">${row.unit}</td>
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">${row.dispensed_by}</td>
                    </tr>`;
                });

                tbody.innerHTML = rows;
            })
            .catch(err => {
                console.error("Error loading medicine history:", err);
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:20px; color:#dc2626;">Error loading medicine history</td></tr>`;
            });
    }

    // ================= CONSULTATION HISTORY TIMELINE (Phase 2) =================
    let chAllConsultations = [];

    function chEsc(s) {
        return String(s).replace(/[&<>"']/g, m => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
        }[m]));
    }
    function chFormatDate(d) {
        if (!d) return '-';
        const parts = d.split('-');
        if (parts.length !== 3) return d;
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        return months[parseInt(parts[1], 10) - 1] + ' ' + parts[2] + ', ' + parts[0];
    }

    function fetchConsultations(patientId) {
        fetch(`/patients/${patientId}/consultations`)
            .then(res => res.json())
            .then(data => {
                chAllConsultations = data.consultations || [];
                chPopulateProviderFilter();
                chRenderList(chAllConsultations);
            })
            .catch(err => console.error('Error loading consultations:', err));
    }

    function chPopulateProviderFilter() {
        const sel = document.getElementById('chFilterProvider');
        if (!sel) return;
        const providers = [...new Set(chAllConsultations.map(c => c.provider_name).filter(Boolean))];
        sel.innerHTML = '<option value="">All Providers</option>' +
            providers.map(p => '<option value="' + chEsc(p) + '">' + chEsc(p) + '</option>').join('');
    }

    function chRenderList(list) {
        const container = document.getElementById('ch-list');
        const empty = document.getElementById('ch-empty');
        if (!container || !empty) return;

        if (!list.length) {
            container.style.display = 'none';
            empty.style.display = 'flex';
            return;
        }
        empty.style.display = 'none';
        container.style.display = 'flex';
        container.className = 'mhx-ch-list';

        container.innerHTML = list.map((c, i) => {
            const noteParts = [];
            if (c.chief_complaint) noteParts.push(c.chief_complaint);
            else if (c.assessment_diagnosis) noteParts.push(c.assessment_diagnosis);
            const note = noteParts.length ? chEsc(noteParts.join(' &middot; ')) : 'No summary recorded';

            return '<div class="mhx-ch-item">' +
                '<div class="mhx-ch-summary">' +
                    '<div class="mhx-ch-left">' +
                        '<div class="date-type">' + chFormatDate(c.consultation_date) + ' &middot; <span class="type-tag">' + chEsc(c.type_label) + '</span></div>' +
                        '<div class="provider-note">' + chEsc(c.provider_name || 'Unknown Provider') + ' &middot; ' + note + '</div>' +
                    '</div>' +
                    '<a class="mhx-ch-view-link" href="/consultations/' + c.id + '">View Details</a>' +
                '</div>' +
            '</div>';
        }).join('');
    }

    function chApplyFilters() {
        const q = (document.getElementById('chSearch')?.value || '').toLowerCase();
        const type = document.getElementById('chFilterType')?.value || '';
        const provider = document.getElementById('chFilterProvider')?.value || '';
        const from = document.getElementById('chFilterFrom')?.value || '';
        const to = document.getElementById('chFilterTo')?.value || '';

        const filtered = chAllConsultations.filter(c => {
            if (type && c.consultation_type !== type) return false;
            if (provider && c.provider_name !== provider) return false;
            if (from && c.consultation_date < from) return false;
            if (to && c.consultation_date > to) return false;
            if (q) {
                const haystack = [c.chief_complaint, c.assessment_diagnosis, c.provider_name, c.type_label]
                    .filter(Boolean).join(' ').toLowerCase();
                if (!haystack.includes(q)) return false;
            }
            return true;
        });
        chRenderList(filtered);
    }

    function chClearFilters() {
        document.getElementById('chSearch').value = '';
        document.getElementById('chFilterType').value = '';
        document.getElementById('chFilterProvider').value = '';
        document.getElementById('chFilterFrom').value = '';
        document.getElementById('chFilterTo').value = '';
        chRenderList(chAllConsultations);
    }

    // ================= MEDICAL HISTORY (NCD) RENDERER v2 =================
    const MH_DASH = '-';

    function mhEsc(s) {
        return String(s).replace(/[&<>"']/g, m => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
        }[m]));
    }
    function mhVal(x, suffix) {
        if (x === null || x === undefined || x === '') return MH_DASH;
        return mhEsc(x) + (suffix || '');
    }
    function mhYN(x) {
        if (x === 'Yes' || x === 'Oo') return '<span class="mh-yes">Yes</span>';
        if (x === 'No' || x === 'Hindi') return '<span class="mh-no">No</span>';
        return '<span class="mh-no">' + MH_DASH + '</span>';
    }
    function mhBool(x) {
        return x ? '<span class="mh-yes">Yes</span>' : '<span class="mh-no">No</span>';
    }
    function mhRow(label, value, strong) {
        return '<div class="mh-row"><div class="k">' + label + '</div>' +
               '<div class="val' + (strong ? ' strong' : '') + '">' + value + '</div></div>';
    }
    function mhSection(title, note, bodyHtml, openByDefault, twoCol) {
        return '<details class="mh-sec' + (twoCol ? ' mh-cols2' : '') + '"' + (openByDefault ? ' open' : '') + '>' +
               '<summary><span>' + title + '</span>' +
               '<span class="mh-sec-right">' +
               (note ? '<span class="mh-sec-note">' + note + '</span>' : '') +
               '<span class="mh-chevron"></span></span>' +
               '</summary><div class="mh-body">' + bodyHtml + '</div></details>';
    }

function renderMedicalHistory(ncd) {
        const assessorName = (ncd.assessed_by_relation && ncd.assessed_by_relation.name)
            || (ncd.assessed_by && ncd.assessed_by.name)
            || MH_DASH;

        // 1. Conditions Present
        const conds = [
            ['Diabetes', ncd.is_diabetic],
            ['Hypertension', ncd.is_hypertensive],
            ['Cancer', ncd.has_cancer],
            ['COPD', ncd.has_copd],
            ['Eye Disease', ncd.has_eye_disease]
        ];
        const condsActive = conds.filter(c => c[1]).map(c => c[0]);

        // 2. Chest Pain Screening
        const cpQFields = ['cp1','cp2','cp3','cp4','cp5','cp6','cp7','cp8'];
        const cpYes = cpQFields.filter(f => ncd[f] === 'Yes').length;
        const cpAlert = ['cp4','cp5','cp6','cp7','cp8'].some(f => ncd[f] === 'Yes');

        // 3. Family History
        const famMap = [
            ['fam_hypertension', 'Hypertension'],
            ['fam_heart_disease', 'Heart Disease'],
            ['fam_stroke', 'Stroke'],
            ['fam_diabetes', 'Diabetes'],
            ['fam_cancer', 'Cancer'],
            ['fam_kidney_disease', 'Kidney Disease'],
            ['fam_lung_disease', 'Lung Disease']
        ];
        const famActive = famMap.filter(f => ncd[f[0]]).map(f => f[1]);

        // Flags Count Calculation
        const lifestyleFlags = [
            ['risk_smoker', 'Smoker'],
            ['risk_binge', 'Binge Drinker'],
            ['risk_activity', 'Insufficient Activity'],
            ['risk_stress', 'High Stress']
        ].filter(f => ncd[f[0]]).map(f => f[1]);

        const allFlagLabels = [...condsActive, ...lifestyleFlags];
        const flagCount = allFlagLabels.length + (cpAlert ? 1 : 0);

        let html = `
            <div class="mhx-summary-container">
                <div class="mhx-summary-top">
                    <div>
                        <h4 class="mhx-summary-heading">NCD Risk Assessment Summary</h4>
                        <div class="mhx-summary-subheading">
                            Assessed ${mhVal(ncd.assessment_date)} &middot; ${mhEsc(assessorName)}${ncd.health_facility ? ' &middot; ' + mhEsc(ncd.health_facility) : ''}
                        </div>
                    </div>
                    <span class="mhx-pill-badge ${flagCount === 0 ? 'none' : ''}">
                        ${flagCount === 0 ? 'No flags' : flagCount + ' risk flag' + (flagCount > 1 ? 's' : '')}
                    </span>
                </div>

                <div class="mhx-section-list">
                    <!-- 1. Vitals -->
                    <div class="mhx-section-item">
                        <div class="mhx-section-title">Vitals</div>
                        <div class="mhx-section-body mhx-metric-grid">
                            <span><b>BP:</b> ${mhVal(ncd.bp_b || ncd.bp_l)}</span>
                            <span><b>BMI:</b> ${mhVal(ncd.bmi)} ${ncd.bmi_s ? `(${mhEsc(ncd.bmi_s)})` : ''}</span>
                            <span><b>Risk Score:</b> ${ncd.risk_score ? mhEsc(ncd.risk_score) + '%' : (ncd.r_30 ? '&ge;30%' : '---')}</span>
                        </div>
                    </div>

                    <!-- 2. Blood Sugar -->
                    <div class="mhx-section-item">
                        <div class="mhx-section-title">Blood Sugar</div>
                        <div class="mhx-section-body mhx-metric-grid">
                            <span><b>FBS:</b> ${mhVal(ncd.fbs_s, ncd.fbs_s ? ' mg/dL' : '')}</span>
                            ${ncd.r_dm_f ? '<span class="mhx-tag-item alert">DM Flag Raised</span>' : ''}
                            ${ncd.r_predm ? '<span class="mhx-tag-item alert">Pre-DM Flag Raised</span>' : ''}
                        </div>
                    </div>

                    <!-- 3. Conditions Present -->
                    <div class="mhx-section-item">
                        <div class="mhx-section-title">Conditions Present</div>
                        <div class="mhx-section-body">
                            ${condsActive.length 
                                ? `<div class="mhx-tags">${condsActive.map(c => `<span class="mhx-tag-item alert">${mhEsc(c)}</span>`).join('')}</div>`
                                : '<span style="color: #9CA3AF;">None recorded</span>'}
                        </div>
                    </div>

                    <!-- 4. Chest Pain Screening -->
                    <div class="mhx-section-item">
                        <div class="mhx-section-title">Chest Pain Screening</div>
                        <div class="mhx-section-body">
                            ${cpAlert 
                                ? `<span class="mhx-tag-item alert">&#9888; Positive screening (${cpYes} of 8 positive)</span>`
                                : (cpYes > 0 
                                    ? `<span>${cpYes} of 8 positive (Low risk)</span>`
                                    : '<span style="color: #166534;">Negative (No symptoms reported)</span>')}
                        </div>
                    </div>

                    <!-- 5. Family History -->
                    <div class="mhx-section-item">
                        <div class="mhx-section-title">Family History</div>
                        <div class="mhx-section-body">
                            ${famActive.length 
                                ? `<div class="mhx-tags">${famActive.map(f => `<span class="mhx-tag-item">${mhEsc(f)}</span>`).join('')}</div>`
                                : '<span style="color: #9CA3AF;">None recorded</span>'}
                        </div>
                    </div>
                </div>

                <a class="mhx-summary-btn" href="/patients/${ncd.patient_id}/ncd-assessment">
                    View Full Assessment &rarr;
                </a>
            </div>
        `;

        document.getElementById('mh-render').innerHTML = html;
    }
    // ========== TRIAGE GATE: block NCD Assessment / New Consultation ==========
    // unless the patient currently has an active triage record.
    function showMhxToast(message) {
        let toast = document.getElementById('mhxToast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'mhxToast';
            toast.className = 'mhx-toast';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        toast.classList.add('show');
        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 3200);
    }

    function applyTriageGate(hasActiveTriage, patientId) {
        const blockMsg = 'This patient must currently be in the Triage Queue before this can be started.';
        const btnNcd = document.getElementById('btn-start-ncd');
        const btnRx1 = document.getElementById('chNewConsultationBtn');
        const btnRx2 = document.getElementById('chNewConsultationBtnEmpty');

        [btnNcd, btnRx1, btnRx2].forEach(function (btn) {
            if (!btn) return;
            if (hasActiveTriage) {
                btn.classList.remove('mhx-disabled-btn');
                btn.removeAttribute('title');
                btn.onclick = null;
            } else {
                btn.classList.add('mhx-disabled-btn');
                btn.title = blockMsg;
                btn.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (typeof showMhxToast === 'function') { showMhxToast(blockMsg); } else { alert(blockMsg); }
                    return false;
                };
            }
        });
    }

    
    // --- MAIN: Open Modal & Fetch Data ---
    function showResidencyProof() {
        const img = document.getElementById('residencyProofImg');
        const emptyMsg = document.getElementById('residencyProofEmpty');
        const url = activePatientData ? activePatientData.residency_proof_url : null;

        if (url) {
            img.src = url;
            img.style.display = 'block';
            emptyMsg.style.display = 'none';
        } else {
            img.style.display = 'none';
            emptyMsg.style.display = 'block';
        }

        document.getElementById('residencyProofModal').style.display = 'flex';
    }

    function closeResidencyProof() {
        document.getElementById('residencyProofModal').style.display = 'none';
    }

    window.openPatientModal = function(id, initialTab = 'basic-info') {
        const modal = document.getElementById('patientDetailModal');
        modal.style.display = 'flex';

        fetch(`/patients/show/${id}`)
            .then(response => response.json())
            .then(data => {
                activePatientData = data; // Save patient object
                fetchConsultations(data.id);
                const newBtn1 = document.getElementById('chNewConsultationBtn');
                const newBtn2 = document.getElementById('chNewConsultationBtnEmpty');
                const newHref = `/patients/${data.id}/consultations/create`;
                if (newBtn1) newBtn1.href = newHref;
                if (newBtn2) newBtn2.href = newHref;
                document.getElementById('detInternalId').innerText = data.id;

                const tabSelector = `.tab-item[onclick*="'${initialTab}'"]`;
                const basicTab = document.querySelector(tabSelector) || document.querySelector('.tab-item:first-child');
                switchTab({ currentTarget: basicTab }, initialTab);

                // Header Mapping
                document.getElementById('headerPatientID').innerText = data.patient_id || '---';
                document.getElementById('headerPatientName').innerText = `${data.first_name || ''} ${data.last_name || ''}`;
                document.getElementById('detID').innerText = data.patient_id || '---';
                document.getElementById('detFam').innerText = data.family_number || '---';

                console.log(data);
console.log("data.id =", data.id);
console.log("data.patient_id =", data.patient_id);

                loadMedicineHistory(data.patient_id);

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

                // --- Immunization & Maternal Health tabs: show only if tracking is enabled ---
                const immTab = document.getElementById('tab-immunization');
                const matTab = document.getElementById('tab-maternal');

                if (String(data.tracking_immunization).toLowerCase() === 'yes') {
                    immTab.style.display = 'block';

                    const vacFields = [
                        'vac_bcg','vac_hepa','vac_penta1','vac_opv1','vac_pcv1',
                        'vac_ipv1','vac_ipv2','vac_penta2','vac_opv2','vac_pcv2',
                        'vac_penta3','vac_opv3','vac_pcv3','vac_mr1','vac_mmr1',
                        'vac_mmr2','vac_hpv1','vac_hpv2','vac_flu','vac_pneumo','vac_td'
                    ];
                    vacFields.forEach(function(field) {
                        const el = document.getElementById('v_' + field);
                        if (el) el.innerText = data[field] || '---';
                    });
                } else {
                    immTab.style.display = 'none';
                }

                if (String(data.tracking_maternal).toLowerCase() === 'yes') {
                    matTab.style.display = 'block';

                    const matFields = {
                        'v_mat_nbs': 'mat_nbs',
                        'v_mat_nbs_date': 'mat_nbs_date',
                        'v_mat_nbs_result': 'mat_nbs_result',
                        'v_mat_hearing': 'mat_hearing',
                        'v_mat_hearing_date': 'mat_hearing_date',
                        'v_mat_hearing_result': 'mat_hearing_result',
                        'v_mat_birth_order': 'mat_birth_order',
                        'v_mat_birth_length': 'mat_birth_length',
                        'v_mat_birth_weight': 'mat_birth_weight',
                        'v_mat_del_type': 'mat_delivery_type',
                        'v_mat_feed_type': 'mat_feeding_type',
                        'v_mat_attendant': 'mat_attendant',
                        'v_mat_del_place': 'mat_delivery_place',
                        'v_mat_vit_a_dose': 'mat_vit_a_dose',
                        'v_mat_vit_a_date': 'mat_vit_a_date',
                        'v_mat_deworm1': 'mat_deworming_1',
                        'v_mat_deworm2': 'mat_deworming_2',
                        'v_ob_g': 'ob_g',
                        'v_ob_menarche': 'ob_menarche',
                        'v_ob_pmp': 'ob_pmp',
                        'v_ob_lmp': 'ob_lmp',
                        'v_ob_edc': 'ob_edc',
                        'v_ob_tt_status': 'ob_tt_status',
                        'v_ob_td1': 'ob_td1',
                        'v_ob_td2': 'ob_td2',
                        'v_ob_td3': 'ob_td3',
                        'v_ob_td4': 'ob_td4',
                        'v_ob_td5': 'ob_td5',
                    };
                    Object.keys(matFields).forEach(function(elId) {
                        const el = document.getElementById(elId);
                        if (el) el.innerText = data[matFields[elId]] || '---';
                    });

                    const obPEl = document.getElementById('v_ob_p');
                    if (obPEl) {
                        const t = data.ob_p_t || '0';
                        const p = data.ob_p_p || '0';
                        const a = data.ob_p_a || '0';
                        const l = data.ob_p_l || '0';
                        obPEl.innerText = t + '-' + p + '-' + a + '-' + l;
                    }
                } else {
                    matTab.style.display = 'none';
                }

                // Rest of medical history mapping
                const ncd = data.latest_ncd_assessment;
                if (!ncd) {
                    document.getElementById('medical-history-empty').style.display = 'flex';
                    document.getElementById('medical-history-filled').style.display = 'none';
                    const btnNcd = document.getElementById('btn-start-ncd');
                    if (btnNcd) btnNcd.href = `/patients/${data.id}/ncd-assessment`;
                } else {
                    document.getElementById('medical-history-empty').style.display = 'none';
                    document.getElementById('medical-history-filled').style.display = 'block';
                    renderMedicalHistory(ncd);
                }

                applyTriageGate(data.has_active_triage_today, data.id);
            })
            .catch(error => console.error('Error loading patient:', error));
    };

    // --- EDIT PATIENT & UPDATE LOGIC ---
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
                    if (['detID', 'detAge', 'detFam'].includes(p.id)) return;

                    const currentValue = p.innerText === '---' ? '' : p.innerText;
                    
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
                        return;
                    }

                    let input;
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
                            input.onchange = window.togglePhilHealthFields;
                        } else {
                            options.forEach(opt => {
                                const option = new Option(opt, opt);
                                if(opt === currentValue) option.selected = true;
                                input.add(option);
                            });
                        }
                    } else {
                        input = document.createElement('input');
                        input.type = (p.id === 'detDOB' || p.id === 'detPhilMemberDob') ? 'date' : 'text';
                        input.value = currentValue;
                        
                        if (p.id === 'detDOB' || p.id === 'detPhilMemberDob') {
                            input.max = new Date().toISOString().split('T')[0];
                        }
                        
                        if (p.id === 'detContact') {
                            input.maxLength = 11;
                            input.placeholder = "09XXXXXXXXX";
                            input.oninput = function() { this.value = this.value.replace(/[^0-9]/g, ''); };
                        }
                        
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

                window.togglePhilHealthFields();

                document.getElementById('dynamic-action-button').innerHTML = 
                    `<button class="btn-primary" onclick="savePatientUpdate('${patientId}')">Save Changes</button>
                     <button class="btn-secondary" style="margin-left:8px;" onclick="location.reload()">Cancel</button>`;
            }
        });
    };

window.savePatientUpdate = function(dbId) {

    const contactNo = document.getElementById('detContact')?.value || '';

    // Required name validation
    const firstName = document.getElementById('edit_first_name')?.value.trim() || '';
    const lastName = document.getElementById('edit_last_name')?.value.trim() || '';

    if (firstName === '' || lastName === '') {
        Swal.fire(
            'Warning',
            'Patient First Name and Last Name cannot be empty.',
            'warning'
        );
        return;
    }

    // Contact number validation
    if (contactNo.length > 0 && contactNo.length !== 11) {
        Swal.fire(
            'Warning',
            'Contact Number must be exactly 11 digits.',
            'warning'
        );
        return;
    }

    const philType =
        document.getElementById('detPhilType')?.value.toLowerCase() || 'none';

    const philNo =
        document.getElementById('detPhilNo')?.value || '';

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const updatedData = {

        _token: csrfToken,

        first_name: firstName,
        middle_name:
            document.getElementById('edit_middle_name')?.value.trim() || '',
        last_name: lastName,

        mother_first:
            document.getElementById('mother_first_name')?.value.trim() || '',
        mother_middle:
            document.getElementById('mother_middle_name')?.value.trim() || '',
        mother_last:
            document.getElementById('mother_last_name')?.value.trim() || '',

        father_first:
            document.getElementById('father_first_name')?.value.trim() || '',
        father_middle:
            document.getElementById('father_middle_name')?.value.trim() || '',
        father_last:
            document.getElementById('father_last_name')?.value.trim() || '',

        dob:
            document.getElementById('detDOB')?.value || '',

        pob:
            document.getElementById('detPOB')?.value.trim() || '',

        gender:
            document.getElementById('detSex')?.value || '',

        civil_status:
            document.getElementById('detCivil')?.value || '',

        address:
            document.getElementById('detAddress')?.value.trim() || '',

        barangay:
            document.getElementById('detBrgy')?.value || '',

        // This matches your Patient model
        contact_number: contactNo,

        email:
            document.getElementById('detEmail')?.value.trim() || '',

        osca_pwd_no:
            document.getElementById('detOscaPwd')?.value.trim() || '',

        four_ps_no:
            document.getElementById('det4ps')?.value.trim() || '',

        religion:
            document.getElementById('detReligion')?.value.trim() || '',

        educational_attainment:
            document.getElementById('detEducation')?.value.trim() || '',

        philhealth: philType,

        philhealth_no_member:
            philType === 'member' ? philNo : '',

        philhealth_no_dependent:
            philType === 'dependent' ? philNo : '',

        philhealth_member_name:
            philType === 'dependent'
                ? document.getElementById('detPhilMemberName')?.value.trim() || ''
                : '',

        philhealth_member_dob:
            philType === 'dependent'
                ? document.getElementById('detPhilMemberDob')?.value || ''
                : ''
    };

    console.log('Updating patient:', dbId);
    console.log('Data being sent:', updatedData);

    Swal.fire({
        title: 'Saving...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // ONLY ONE REQUEST
    fetch(`${window.location.origin}/patients/${dbId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(updatedData)
    })
    .then(async response => {

        const contentType = response.headers.get('content-type') || '';

        // Read server response even when there is an error
        const responseText = await response.text();

        console.log('Server status:', response.status);
        console.log('Server response:', responseText);

        if (!response.ok) {
            let message = 'Patient update failed.';

            try {
                const errorData = JSON.parse(responseText);

                if (errorData.message) {
                    message = errorData.message;
                }

                if (errorData.errors) {
                    console.error('Validation errors:', errorData.errors);

                    message = Object.values(errorData.errors)
                        .flat()
                        .join('<br>');
                }

            } catch (e) {
                // Response was not JSON
                if (responseText) {
                    console.error('Server returned:', responseText);
                }
            }

            throw new Error(message);
        }

        // Parse JSON only if server actually returned JSON
        if (contentType.includes('application/json')) {
            return JSON.parse(responseText);
        }

        return {};
    })
    .then(result => {

        console.log('Update successful:', result);

        Swal.fire(
            'Updated!',
            'Patient record saved successfully.',
            'success'
        ).then(() => {

            // Refresh the page after successful save
            location.reload();

        });
    })
    .catch(error => {

        console.error('Patient update error:', error);

        Swal.fire(
            'Error',
            error.message || 'Unable to update patient record.',
            'error'
        );
    });
};

function loadServiceHistory(patientId) {

    const loading = document.getElementById('serviceHistoryLoading');
    const empty = document.getElementById('serviceHistoryEmpty');
    const list = document.getElementById('serviceHistoryList');

    if (!loading || !empty || !list) return;

    loading.style.display = 'block';
    empty.style.display = 'none';
    list.innerHTML = '';

    fetch(`/patients/${patientId}/service-history`)
        .then(async response => {

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(
                    `HTTP ${response.status}: ${errorText}`
                );
            }

            return response.json();
        })
        .then(records => {

            loading.style.display = 'none';

            if (!records || records.length === 0) {
                empty.style.display = 'block';
                return;
            }

            list.innerHTML = '';

            records.forEach(record => {

                const riskClass =
                    record.risk_level?.toLowerCase() === 'high'
                        ? 'background:#FEF2F2;color:#B91C1C;'
                        : record.risk_level?.toLowerCase() === 'moderate'
                        ? 'background:#FFFBEB;color:#B45309;'
                        : 'background:#ECFDF5;color:#047857;';

                const card = document.createElement('div');

                card.className = 'service-history-card';

                card.innerHTML = `
                    <div class="service-history-header">

                        <div>
                            <div class="service-history-date">
                                ${record.date}
                            </div>

                            <div class="service-history-time">
                                ${record.time}
                            </div>

                            <div class="service-history-type">
                                ${record.service_type}
                            </div>
                        </div>

                        <span
                            class="service-history-status"
                            style="${riskClass}">
                            ${record.risk_level}
                        </span>

                    </div>

                    <div class="service-history-body">

                        <div class="service-history-grid">

                            <div class="service-history-field">
                                <span class="service-history-label">
                                    Triage Level
                                </span>

                                <span class="service-history-value">
                                    ${record.triage_level}
                                </span>
                            </div>

                            <div class="service-history-field">
                                <span class="service-history-label">
                                    Status
                                </span>

                                <span class="service-history-value">
                                    ${record.status}
                                </span>
                            </div>

                            <div class="service-history-field">
                                <span class="service-history-label">
                                    Registered By
                                </span>

                                <span class="service-history-value">
                                    ${record.registered_by_name} (${record.registered_by_role})
                                </span>
                            </div>

                        </div>

                        <div class="service-history-vitals">

                            <div class="service-history-vital">
                                <span class="service-history-vital-label">
                                    Blood Pressure
                                </span>

                                <span class="service-history-vital-value">
                                    ${record.bp}
                                </span>
                            </div>

                            <div class="service-history-vital">
                                <span class="service-history-vital-label">
                                    Temperature
                                </span>

                                <span class="service-history-vital-value">
                                    ${record.temp}
                                </span>
                            </div>

                            <div class="service-history-vital">
                                <span class="service-history-vital-label">
                                    Weight
                                </span>

                                <span class="service-history-vital-value">
                                    ${record.weight}
                                </span>
                            </div>

                            <div class="service-history-vital">
                                <span class="service-history-vital-label">
                                    Height
                                </span>

                                <span class="service-history-vital-value">
                                    ${record.height}
                                </span>
                            </div>

                        </div>

                        <div class="service-history-symptoms">

                            <span class="service-history-label">
                                Symptoms / Reason
                            </span>

                            <p>
                                ${record.symptoms}
                            </p>

                        </div>

                    </div>
                `;

                list.appendChild(card);
            });
        })
        .catch(error => {

            console.error('Error loading service history:', error);

            loading.style.display = 'none';

            list.innerHTML = `
                <div style="
                    background: #FEF2F2;
                    border: 1px solid #FECACA;
                    border-radius: 10px;
                    padding: 20px;
                    color: #B91C1C;
                    font-size: 13px;
                ">
                    Unable to load this patient's service history.
                </div>
            `;
        });
}
</script>