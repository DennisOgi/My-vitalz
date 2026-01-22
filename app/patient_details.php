
            <div class="container-xxl flex-grow-1 container-p-y">
             <h4>Patient Details</h4>
                 <div class="card">
                    <div class="card-body">
             <div class="col-md-12 ">
             <div class="row">
             <div class="col-md-2 ">
                 <img
                          src="../assets/<?php if($pat_user[0]->photo!=""){echo "images/".$pat_user[0]->photo;}else{ echo 'img/avatars/user.png'; } ?>"
                          alt="user-avatar"
                          class="d-block rounded-circle"
                          height="130"
                          width="130"
                          id="uploadedAvatar"
                        />
                 </div>
                 
                 <div class="col-md-6 mb-4 ml-4">
                     <p>First Name: <b><?php echo $pat_user[0]->first_name;?></b></p>
                     <p>Last Name: <b><?php echo $pat_user[0]->last_name;?></b></p>
                     <p>Email: <b><?php echo $pat_user[0]->email;?></b></p>
                     <p>Phone: <b><?php echo $pat_user[0]->phone;?></b></p>
                 </div>
                  
                 <div class="col-md-4 mb-4">
                     <a href="?pg=patient_medications_all&ptid=<?php echo $pat_user[0]->ref_code;?>" class="btn btn-primary btn-sm me-2 mb-2"><i class="bx bx-capsule"></i> Medications</a>
                     <a href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>" class="btn btn-info btn-sm me-2 mb-2"><i class="bx bx-time"></i> Readings History</a>
                     
                     <button data-bs-toggle="modal"
                          data-bs-target="#ScheduleAppointment" class="btn btn-warning btn-sm me-2 mb-2"><i class="bx bx-calendar"></i> Schedule Appointment</button> 
                   
                   <button data-bs-toggle="modal"
                          data-bs-target="#RefertoDoctor" class="btn btn-info btn-sm me-2 mb-2"><i class="bx bx-reply"></i> Refer to Another Doctor</button> 
                 </div>
                  
                  </div>
                  </div>
                  </div>
                  </div>
                 
                <div class="row mt-4">
                      
                 <div class="col-md-12">
                <div class="row">
                    <h4>Vital Readings</h4>
                 
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/heart.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-pink h3"><?php if(empty($heart_rate_readings)){echo '00';}else{ echo $heart_rate_readings[count($heart_rate_readings)-1]->reading; }?></span><small class="txt-color-pink"><?php if(empty($heart_rate_readings)){echo '';}else{ echo $heart_rate_readings[count($heart_rate_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Heart rate (ECG)</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($heart_rate_readings)){echo '-/-/-';}else{ echo \App\functions::format_date_time($heart_rate_readings[count($heart_rate_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/blood.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                               <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-purple h3"><?php if(empty($blood_pressure_readings)){echo '0/0';}else{ echo $blood_pressure_readings[count($blood_pressure_readings)-1]->reading; }?></span><small class="txt-color-purple"><?php if(empty($blood_pressure_readings)){echo '';}else{ echo $blood_pressure_readings[count($blood_pressure_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Blood Pressure</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($blood_pressure_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($blood_pressure_readings[count($blood_pressure_readings)-1]->date); }?></small><br>
                          <?php
                          $bpInfo = null;
                          if (!empty($blood_pressure_readings)) {
                              $bpLatest = $blood_pressure_readings[count($blood_pressure_readings)-1];
                              $bpInfo = \App\functions::classify_blood_pressure($bpLatest->reading);
                          } else {
                              $bpInfo = \App\functions::classify_blood_pressure('');
                          }
                          ?>
                          <small class="<?php echo $bpInfo['color']; ?> fw-semibold"><i class="bx <?php echo $bpInfo['icon']; ?>"></i> <?php echo $bpInfo['label']; ?></small><br>
                          <small class="text-muted fw-semibold fs-9">What Next: <?php echo $bpInfo['next_steps']; ?></small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/oxygen.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                               <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-blue h3"><?php if(empty($oxygen_saturation_readings)){echo '00';}else{ echo $oxygen_saturation_readings[count($oxygen_saturation_readings)-1]->reading; }?></span><small class="txt-color-blue"><?php if(empty($oxygen_saturation_readings)){echo '';}else{ echo $oxygen_saturation_readings[count($oxygen_saturation_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Oxygen Saturation</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($oxygen_saturation_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($oxygen_saturation_readings[count($oxygen_saturation_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/stress.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-red h3"><?php if(empty($stress_readings)){echo '00';}else{ echo $stress_readings[count($stress_readings)-1]->reading; }?></span><small class="txt-color-red"><?php if(empty($stress_readings)){echo '';}else{ echo $stress_readings[count($stress_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Stress (HRV rate)</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($stress_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($stress_readings[count($stress_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/glucose.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                               <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-blue-light h3"><?php if(empty($blood_glucose_readings)){echo '00';}else{ echo $blood_glucose_readings[count($blood_glucose_readings)-1]->reading; }?></span><small class="txt-color-blue-light"><?php if(empty($blood_glucose_readings)){echo '';}else{ echo $blood_glucose_readings[count($blood_glucose_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Blood glucose</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($blood_glucose_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($blood_glucose_readings[count($blood_glucose_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/lipid.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                               <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-torquoise h3"><?php if(empty($lipids_readings)){echo '00';}else{ echo $lipids_readings[count($lipids_readings)-1]->reading; }?></span><small class="txt-color-torquoise"><?php if(empty($lipids_readings)){echo '';}else{ echo $lipids_readings[count($lipids_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Lipids</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($lipids_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($lipids_readings[count($lipids_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                     
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/hba1c.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-purple-light h3"><?php if(empty($hba1c_readings)){echo '00';}else{ echo $hba1c_readings[count($hba1c_readings)-1]->reading; }?></span><small class="txt-color-purple-light"><?php if(empty($hba1c_readings)){echo '';}else{ echo $hba1c_readings[count($hba1c_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">HbA1c</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($hba1c_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($hba1c_readings[count($hba1c_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/ihra.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                               <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-green h3"><?php if(empty($ihra_readings)){echo '00';}else{ echo $ihra_readings[count($ihra_readings)-1]->reading; }?></span><small class="txt-color-green"><?php if(empty($ihra_readings)){echo '';}else{ echo $ihra_readings[count($ihra_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">IHRA</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($ihra_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($ihra_readings[count($ihra_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                   <div class="col-lg-3 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="../assets/img/icons/unicons/temperature.png"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="?pg=patient_reading_history&ptid=<?php echo $pat_user[0]->ref_code; ?>">View History</a>
                               
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-orange h3"><?php if(empty($body_temperature_readings)){echo '00';}else{ echo $body_temperature_readings[count($body_temperature_readings)-1]->reading; }?></span><small class="txt-color-orange"><?php if(empty($body_temperature_readings)){echo '';}else{ echo $body_temperature_readings[count($body_temperature_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Body temperature</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($body_temperature_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($body_temperature_readings[count($body_temperature_readings)-1]->date); }?></small><br>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Normal</small>
                        </div>
                      </div>
                    </div>
                    
                </div>
                </div>
                

              </div>
             
            </div>
            <!-- / Content -->

<!--        Modals   -->
          

 <div class="modal fade" id="ScheduleAppointment" data-bs-backdrop="static" tabindex="-1">
   
                          <div class="modal-dialog">
                            <form class="modal-content" method="post">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        
                        <input type = "hidden" name = "schedule_appointment_patient" value = "<?php echo $pat_user[0]->ref_code; ?>">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Schedule an appointment</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                              <div class="modal-body">
                               
                                <div class="row">
                                     <div class="col mb-3">
                                    <label  class="form-label">Select Date</label>
                            <input class="form-control" type="date" name="appointment_date_schedule" id="appt_date" onkeyup="var dt=$('#appt_date').val(); appointmt_time('<?php echo $user[0]->ref_code; ?>', dt);", onblur="var dt=$('#appt_date').val(); appointmt_time('<?php echo $user[0]->ref_code; ?>', dt);" required min="<?php echo date("Y-m-d", strtotime("today")); ?>">
                                 
                             </div>
                             </div>
                             
                                   <div class="row">
                             <div class="col mb-3">
                                    <label  class="form-label">Select time of the day</label>
                            <select class="form-select" name="appointment_time_schedule" id="appt_time" required>
                                   <option value="">--</option>
                                 </select>
                             </div>
                                  </div>
                                  
                                  
                              <div class="row">
                             <div class="col mb-3">
                                    <label  class="form-label">Select channel</label>
                            <select class="form-select" required name="channel">
                                   <option value="">--</option>
                                   <option value="Online Meeting">Online Meeting</option>
                                   <option value="Physical Meeting">Physical Meeting</option>
                                 </select>
                             </div>
                             </div>
                             
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Address / Meeting Link</label>
                                    <input
                                      type="text"
                                      name="address"
                                      class="form-control"
                                      placeholder="Enter meeting address / link"
                                           value="<?php echo $appointment[0]->address; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                                  
                                <div class="row">
                                  <div class="col mb-3">
                                    <label  class="form-label">Consultancy Fee</label>
                                     <input
                                      type="number"
                                      step="0.01"
                                      name="cost"
                                      class="form-control"
                                      placeholder="Enter your consultancy fee"
                                            value="<?php echo $appointment[0]->cost; ?>"
                                           required
                                    />
                                  </div>
                                </div>
                               
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                                <button type="submit" class="btn btn-primary">Schedule</button>
                              </div>
                            </form>
                          </div>
                        
</div>
    
 <div class="modal fade" id="RefertoDoctor" data-bs-backdrop="static" tabindex="-1">
                          <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="backDropModalTitle">Refer to Another Doctor</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"
                                ></button>
                              </div>
                             
                              <div class="modal-body">
                        <label for="defaultFormControlInput" class="form-label">Enter Doctor's Phone Number</label>
                        <input
                          type="text"
                          class="form-control"
                          id="pname"
                          placeholder="Enter doctor's phone number"
                          aria-describedby="defaultFormControlHelp"
                        onkeyup="search_doctor();"
                        />
                        <div id="sresults" class="form-text mt-3">
                         
                        </div>
                          <hr>
                        <div id="public_doctors" class="form-text mt-3">
                            
                        </div>
                          
                      </div>
                      
                            
                             <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                  Close
                                </button>
                               
                              </div>
                          </div>
                          </div>
                        </div>
          