<?php

namespace App;
use Mail;
use DB;

class Functions
{
    //save a new vitalz reading in the database
    public static function save_vital_reading($uid, $vitalz, $reading, $si_unit){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO vital_readings(user, vitalz, reading, si_unit, date) VALUES (?,?,?,?,?)', [$uid, $vitalz, $reading, $si_unit, $dt]);
    }
    
     //request a doctor
    public static function add_doctor($uid, $doctor){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO patients(user, doctor, user_approve, date) VALUES (?,?,?,?)', [$uid, $doctor, "1", $dt]);
    }
    
    
     //request a hospital by doctor
    public static function add_hopital($uid, $hospital){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO patients(user, hospital, doctor_approve, date) VALUES (?,?,?,?)', [$uid, $hospital, "1", $dt]);
    }
    
    //request a doctor by hospital
    public static function add_doctorh($uid, $doctor){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO patients(user, doctor, hospital_approve, date) VALUES (?,?,?,?)', [$uid, $doctor, "1", $dt]);
    }
    
    
     //request a patient
    public static function add_patient($uid, $patient){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO patients(user, doctor, doctor_approve, date) VALUES (?,?,?,?)', [$patient, $uid, "1", $dt]);
    }
    
     //prescribe drug to a patient
    public static function add_prescription($uid, $patient, $drug_name, $drug_type, $duration, $dosage, $frequency, $additional){
        $dt = time(); //date('d-M-Y');
        DB::insert('INSERT INTO prescriptions(user, doctor, drug_name, drug_type, duration, dosage, frequency, additional_info, date) VALUES (?,?,?,?,?,?,?,?,?)', [$patient, $uid, $drug_name, $drug_type, $duration, $dosage, $frequency, $additional, $dt]);
    }
    
     //edit prescription
    public static function edit_prescription($uid, $patient, $drug_name, $drug_type, $duration, $dosage, $frequency, $additional, $pscid){
        
        DB::update('UPDATE prescriptions SET drug_name=?, drug_type=?, duration=?, dosage=?, frequency=?, additional_info=? WHERE id=? AND user=? AND doctor=?', [$drug_name, $drug_type, $duration, $dosage, $frequency, $additional, $pscid, $patient, $uid]);
    }
    
     //book appointment
    public static function book_appointment($uid, $doctor, $appointment_date, $appointment_time, $channel, $symptoms){
        $dt = date('d-M-Y');
        
        $doc_ref= $doctor;
        $doctor_profile =  DB::select('select * from users WHERE ref_code="'.$doc_ref.'"');
        $doc_id = $doctor_profile[0]->id;
        
        $start_time = strtotime($appointment_date." ".$appointment_time);
        $end_time = strtotime("+1 hour", $start_time);
        
        $day = date("D", strtotime($appointment_date));
        
        DB::insert('INSERT INTO appointments(user, doctor, day, date, start_time, end_time, channel, status, booking_date, symptoms) VALUES (?,?,?,?,?,?,?,?,?,?)', [$uid, $doc_id, $day, $appointment_date, $start_time, $end_time, $channel, "pending", $dt, $symptoms]);
    }
    
     //doctor approve a request
    public static function doctor_approve_request($uid, $request_id){
        DB::update('UPDATE patients SET doctor_approve=? WHERE doctor=? AND id=?', ["1", $uid, $request_id]);
    }
    
     //doctor decline a request
    public static function doctor_decline_request($uid, $request_id){
        DB::update('UPDATE patients SET doctor_approve=? WHERE doctor=? AND id=?', ["2", $uid, $request_id]);
    }
    
     //patient approve a request
    public static function patient_approve_request($uid, $request_id){
        DB::update('UPDATE patients SET user_approve=? WHERE user=? AND id=?', ["1", $uid, $request_id]);
    }
    
     //patient decline a request
    public static function patient_decline_request($uid, $request_id){
        DB::update('UPDATE patients SET user_approve=? WHERE user=? AND id=?', ["2", $uid, $request_id]);
    }
    
     //add or update appointment schedule
    public static function add_appointment_schedule($uid, $monday_start, $monday_end, $tuesday_start, $tuesday_end, $wednesday_start, $wednesday_end, $thursday_start, $thursday_end, $friday_start, $friday_end, $saturday_start, $saturday_end, $sunday_start, $sunday_end){
        
        $user =  DB::select('select * from appointment_schedule WHERE user='.$uid);
        
        if(empty($user)){ 
        DB::insert('INSERT INTO appointment_schedule(user, monday_start, monday_end, tuesday_start, tuesday_end, wednesday_start, wednesday_end, thursday_start, thursday_end, friday_start, friday_end, saturday_start, saturday_end, sunday_start, sunday_end) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [$uid, $monday_start, $monday_end, $tuesday_start, $tuesday_end, $wednesday_start, $wednesday_end, $thursday_start, $thursday_end, $friday_start, $friday_end, $saturday_start, $saturday_end, $sunday_start, $sunday_end]);
            
        }else{
            DB::update('UPDATE appointment_schedule SET monday_start=?, monday_end=?, tuesday_start=?, tuesday_end=?, wednesday_start=?, wednesday_end=?, thursday_start=?, thursday_end=?, friday_start=?, friday_end=?, saturday_start=?, saturday_end=?, sunday_start=?, sunday_end=? WHERE user=?', [$monday_start, $monday_end, $tuesday_start, $tuesday_end, $wednesday_start, $wednesday_end, $thursday_start, $thursday_end, $friday_start, $friday_end, $saturday_start, $saturday_end, $sunday_start, $sunday_end, $uid]);
        }
    }
    
    
     //add product
    public static function add_product($uid, $name, $price, $description, $photo, $category){
        $sku = "MPD".$uid.mt_rand(1000,9999);
        $dt = date('d-M-Y');
        DB::insert('INSERT INTO products(user, name, sku, price, description, photo, category, date) VALUES (?,?,?,?,?,?,?,?)', [$uid, $name, $sku, $price, $description, $photo, $category, $dt]);
    }
    
     //edit product
    public static function edit_product($uid, $pid, $name, $price, $description, $photo, $category){
        
        DB::update('UPDATE products SET name=?, price=?, description=?, photo=?, category=? WHERE id=? AND user=?', [$name, $price, $description, $photo, $category, $pid, $uid]);
    }
    
     //edit store ref product
    public static function edit_product_ref($uid, $pid, $price){
        
        DB::update('UPDATE products SET price=? WHERE id=? AND user=?', [$price, $pid, $uid]);
    }
    
    
     //add store product
    public static function add_store_product($uid, $product_ref, $price){
        $dt = date('d-M-Y');
        DB::insert('INSERT INTO products(user,  product_ref, price, date) VALUES (?,?,?,?)', [$uid, $product_ref, $price, $dt]);
    }
    
    
     //create support ticket
    public static function add_support($uid, $ticket_id, $subject, $description, $priority, $status){
        $dt = date('d-M-Y h:ia');
        DB::insert('INSERT INTO support(user, ticket_id, subject, description, priority, status, date, last_updated) VALUES (?,?,?,?,?,?,?,?)', [$uid, $ticket_id, $subject, $description, $priority, $status, $dt, $dt]);
    }
    
    
     //add support reply
    public static function add_support_reply($uid, $ticket_id, $comment){
        $dt = date('d-M-Y h:ia');
        DB::insert('INSERT INTO support_replies(user, support_id, comment, date) VALUES (?,?,?,?)', [$uid, $ticket_id, $comment, $dt]);
        
        DB::update('UPDATE support SET last_updated=? WHERE ticket_id=?', [$dt, $ticket_id]);
    }
    
    
     //record taken medications
    public static function take_medication($uid, $prescription_id, $period_taken){
        $dt = date('d-M-Y h:ia');
        DB::insert('INSERT INTO medications(user, prescription_id, period_taken, date) VALUES (?,?,?,?)', [$uid, $prescription_id, $period_taken, $dt]);
    }
    
    
     //add notification
    public static function notify($uid, $description, $link){
        $dt = time();
        DB::insert('INSERT INTO notifications(user, description, link, date) VALUES (?,?,?,?)', [$uid, $description, $link, $dt]);
    }
    
     //get si units
    public static function get_si_units($vital){
        $si_units =  DB::select('select * from allvitalz WHERE name="'.$vital.'"');
        $si_array = explode(",", $si_units[0]->si_unit);
        $list = "<option value=''>Select unit</option>";
        if(!empty($si_array)){
            foreach($si_array as $si){
                $list.="<option value='".$si."'>".$si."</option>";
            }
        }
        
        return $list;
    }
    
    
    //format date of readings
    public static function format_date_time($time_secs){
        $sec = time()-$time_secs; 
        $formatted="";
        
        if($sec>86400){ 
            $formatted= date('d-M-Y', $time_secs);
            
        }else if($sec<86400 && $sec>3600){ 
            $formatted= floor($sec/3600). " hrs ago";
                           
        }else if($sec<3600 && $sec>60){ 
            $formatted= floor($sec/60). " min ago";
                           
        }else { 
            $formatted= $sec. " secs ago";
                           
        }
        
        return $formatted;
    }

    public static function parse_blood_pressure($reading)
    {
        if (!is_string($reading)) {
            return null;
        }

        $reading = trim($reading);

        if (!preg_match('/^(\d{2,3})\s*\/\s*(\d{2,3})$/', $reading, $m)) {
            return null;
        }

        $systolic = intval($m[1]);
        $diastolic = intval($m[2]);

        if ($systolic <= 0 || $diastolic <= 0) {
            return null;
        }

        if ($systolic < $diastolic) {
            return null;
        }

        if ($systolic < 50 || $systolic > 300 || $diastolic < 30 || $diastolic > 200) {
            return null;
        }

        return [
            'systolic' => $systolic,
            'diastolic' => $diastolic,
        ];
    }

    public static function classify_blood_pressure($reading)
    {
        $parsed = self::parse_blood_pressure($reading);
        if ($parsed === null) {
            return [
                'label' => 'Unknown',
                'classification' => 'Unable to classify',
                'next_steps' => 'Please enter blood pressure in the format systolic/diastolic (e.g., 120/80).',
                'color' => 'text-muted',
                'icon' => 'bx-minus',
                'systolic' => null,
                'diastolic' => null,
            ];
        }

        $sys = $parsed['systolic'];
        $dia = $parsed['diastolic'];

        if ($sys >= 180 || $dia >= 120) {
            return [
                'label' => 'Too High',
                'classification' => 'According to WHO standards your BP is too high',
                'next_steps' => 'Please take your drugs immediately and consult your virtual doctor immediately or visit your physician.',
                'color' => 'text-danger',
                'icon' => 'bx-error-circle',
                'systolic' => $sys,
                'diastolic' => $dia,
            ];
        }

        if ($sys >= 160 || $dia >= 100) {
            return [
                'label' => 'Very High',
                'classification' => 'According to WHO standards your BP is very high',
                'next_steps' => 'Please take your drugs as prescribed and consult your virtual doctor.',
                'color' => 'text-danger',
                'icon' => 'bx-error-circle',
                'systolic' => $sys,
                'diastolic' => $dia,
            ];
        }

        if ($sys >= 140 || $dia >= 90) {
            return [
                'label' => 'High',
                'classification' => 'According to WHO standards your BP is high',
                'next_steps' => 'Please take your drugs as prescribed and consult your virtual doctor.',
                'color' => 'text-warning',
                'icon' => 'bx-error',
                'systolic' => $sys,
                'diastolic' => $dia,
            ];
        }

        if ($sys >= 130 || $dia >= 85) {
            return [
                'label' => 'Slightly High',
                'classification' => 'According to WHO standards your BP is slightly high',
                'next_steps' => 'Please monitor your blood pressure and consult your virtual doctor if it stays high.',
                'color' => 'text-warning',
                'icon' => 'bx-error',
                'systolic' => $sys,
                'diastolic' => $dia,
            ];
        }

        if ($sys <= 100 || $dia <= 60) {
            return [
                'label' => 'Very Low',
                'classification' => 'According to WHO standards your BP is very low',
                'next_steps' => 'Consult your virtual doctor immediately or visit your physician, especially if you feel weak, dizzy, or faint.',
                'color' => 'text-danger',
                'icon' => 'bx-error-circle',
                'systolic' => $sys,
                'diastolic' => $dia,
            ];
        }

        return [
            'label' => 'Normal',
            'classification' => 'According to WHO standards your BP is normal',
            'next_steps' => 'Continue healthy habits and take your medications as prescribed.',
            'color' => 'text-success',
            'icon' => 'bx-check',
            'systolic' => $sys,
            'diastolic' => $dia,
        ];
    }
    
    
}