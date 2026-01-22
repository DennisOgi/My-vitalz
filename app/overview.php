
            <div class="row">
              <div class="col-12">
                <h5 class="mb-3">Vital Readings</h5>
              </div>
            </div>
            <div class="row">
                 
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
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
                                <a class="dropdown-item" href="javascript:void(0);">View History</a>
                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                          data-bs-target="#DropModalReadings">Take Readings</a>
                               
                              </div>
                            </div>
                          </div>
                          
                          <span class="card-title mb-2 txt-color-orange h3"><?php if(empty($body_temperature_readings)){echo '00';}else{ echo $body_temperature_readings[count($body_temperature_readings)-1]->reading; }?></span><small class="txt-color-orange"><?php if(empty($body_temperature_readings)){echo '';}else{ echo $body_temperature_readings[count($body_temperature_readings)-1]->si_unit; }?></small><br>
                        <span class="fw-semibold d-block mb-1">Body temperature</span>
                          <small class="text-muted fw-semibold fs-9">Measured <?php if(empty($body_temperature_readings)){echo '-/-/-'; }else{ echo \App\functions::format_date_time($body_temperature_readings[count($body_temperature_readings)-1]->date); }?></small><br>
                  </div>