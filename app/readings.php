
            <div class="row">
              <div class="col-12">
                <h5 class="mb-1">Vital Readings</h5>
                <p class="text-muted mb-3">Review your latest measurements and history by vital type.</p>
              </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    
               
                  <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link active"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-heart-rate"
                          aria-controls="navs-heart-rate"
                          aria-selected="true"
                        >
                          Heart rate (ECG)
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-blood-pressure"
                          aria-controls="navs-blood-pressure"
                          aria-selected="false"
                        >
                          Blood Pressure
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-oxygen-saturation"
                          aria-controls="navs-oxygen-saturation"
                          aria-selected="false"
                        >
                          Oxygen Saturation
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-stress"
                          aria-controls="navs-stress"
                          aria-selected="false"
                        >
                          Stress (HRV rate)
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-blood-glucose"
                          aria-controls="navs-blood-glucose"
                          aria-selected="false"
                        >
                          Blood glucose
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-lipids"
                          aria-controls="navs-lipids"
                          aria-selected="false"
                        >
                          Lipids
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-hba1c"
                          aria-controls="navs-hba1c"
                          aria-selected="false"
                        >
                          HbA1c
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-ihra"
                          aria-controls="navs-ihra"
                          aria-selected="false"
                        >
                          IHRA
                        </button>
                      </li>
                      <li class="nav-item">
                        <button
                          type="button"
                          class="nav-link"
                          role="tab"
                          data-bs-toggle="tab"
                          data-bs-target="#navs-temperature"
                          aria-controls="navs-temperature"
                          aria-selected="false"
                        >
                          Body temperature
                        </button>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-heart-rate" role="tabpanel">
                          <h6 class="mb-3">Heart rate (ECG) Readings</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        if(!empty($heart_rate_readings)){
                        for($i=count($heart_rate_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                        <td><?php  echo \App\functions::format_date_time($heart_rate_readings[$i]->date); ?></td>
                        <td><?php  echo $heart_rate_readings[$i]->reading." ".$heart_rate_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-blood-pressure" role="tabpanel">
                         <h6 class="mb-3">Blood Pressure</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        <th>Status</th>
                        <th>What Next</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                       <?php
                        if(!empty($blood_pressure_readings)){
                        for($i=count($blood_pressure_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($blood_pressure_readings[$i]->date); ?></td>
                        <td><?php  echo $blood_pressure_readings[$i]->reading." ".$blood_pressure_readings[$i]->si_unit; ?></td>
                        <?php $bpInfo = \App\functions::classify_blood_pressure($blood_pressure_readings[$i]->reading); ?>
                        <td class="<?php echo $bpInfo['color']; ?> fw-semibold"><i class="bx <?php echo $bpInfo['icon']; ?>"></i> <?php echo $bpInfo['label']; ?></td>
                        <td><?php echo $bpInfo['next_steps']; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-oxygen-saturation" role="tabpanel">
                       <h6 class="mb-3">Oxygen Saturation</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        if(!empty($oxygen_saturation_readings)){
                        for($i=count($oxygen_saturation_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($oxygen_saturation_readings[$i]->date); ?></td>
                        <td><?php  echo $oxygen_saturation_readings[$i]->reading." ".$oxygen_saturation_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-stress" role="tabpanel">
                         <h6 class="mb-3">Stress (HRV rate)</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <?php
                        if(!empty($stress_readings)){
                        for($i=count($stress_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($stress_readings[$i]->date); ?></td>
                        <td><?php  echo $stress_readings[$i]->reading." ".$stress_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-blood-glucose" role="tabpanel">
                       <h6 class="mb-3">Blood glucose</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                       <?php
                        if(!empty($blood_glucose_readings)){
                        for($i=count($blood_glucose_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($blood_glucose_readings[$i]->date); ?></td>
                        <td><?php  echo $blood_glucose_readings[$i]->reading." ".$blood_glucose_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-lipids" role="tabpanel">
                       <h6 class="mb-3">Lipids</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                     <?php
                        if(!empty($lipids_readings)){
                        for($i=count($lipids_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($lipids_readings[$i]->date); ?></td>
                        <td><?php  echo $lipids_readings[$i]->reading." ".$lipids_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-hba1c" role="tabpanel">
                    <h6 class="mb-3">HbA1c</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                     <?php
                        if(!empty($hba1c_readings)){
                        for($i=count($hba1c_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($hba1c_readings[$i]->date); ?></td>
                        <td><?php  echo $hba1c_readings[$i]->reading." ".$hba1c_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-ihra" role="tabpanel">
                        <h6 class="mb-3">IHRA</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                       <?php
                        if(!empty($ihra_readings)){
                        for($i=count($ihra_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($ihra_readings[$i]->date); ?></td>
                        <td><?php  echo $ihra_readings[$i]->reading." ".$ihra_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                      <div class="tab-pane fade" id="navs-temperature" role="tabpanel">
                         <h6 class="mb-3">Body temperature</h6>
                      <table class="table table-sm table-hover align-middle">
                    <thead>
                      <tr>
                          <th>Date</th>
                        <th>Readings</th>
                        
                        
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    <?php
                        if(!empty($body_temperature_readings)){
                        for($i=count($body_temperature_readings)-1; $i>=0; $i--){
                            ?>
                      <tr>
                          <td><?php  echo \App\functions::format_date_time($body_temperature_readings[$i]->date); ?></td>
                        <td><?php  echo $body_temperature_readings[$i]->reading." ".$body_temperature_readings[$i]->si_unit; ?></td>
                        
                        
                      </tr>
                     <?php
                        }
                        }else{
                            ?>
                        <tr><td>No Readings</td></tr>
                        <?php
                        }
                            
                            ?>
                    </tbody>
                  </table>
                      </div>
                        
                    </div>
                  </div>
               
                    </div>
            

              </div>

           
          