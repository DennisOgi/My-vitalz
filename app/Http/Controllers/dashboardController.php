<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cookie;
use App\functions; 
use Illuminate\Support\Facades\Hash;
//use App\base;
use App\cart;



class dashboardController extends Controller
{
	//public $secret_key = 'sk_test_bd7c6cd4cde9c6838585c6c5f5c375b353fcfce6';
	
	 function dashboard(Request $request){
         if(Cookie::get('uid')==""){
           redirect()->to("/")->send();
        }else{
            $uid = Cookie::get('uid');
            $user =  DB::select('select * from users WHERE id='.$uid);
         }
          $pg=$request->input('pg');
         $a_type="";
         $a_message="";
         
         $allvitalz =  DB::select('select * from allvitalz');
         $pending_appointments =  DB::select('select * from appointments WHERE end_time > '.time().' AND user='.$uid);
         $prescriptions =  DB::select('select * from prescriptions WHERE user='.$uid);
         $medications =  DB::select('select * from medications WHERE user='.$uid);
         $my_notifications=  DB::select('select * from notifications WHERE seen IS NULL AND user='.$uid);
         $my_tickets=  DB::select('select * from support WHERE user='.$uid);
         $public_doctors=  DB::select('select * from users WHERE public=1 AND doctor=1');
         
         if($request->input('a_type')){
             $a_type = $this->sanitizeInput($request->input('a_type'));
         $a_message = $this->sanitizeInput($request->input('a_message'));
         }
         
         //add vitalz readings
         if($request->input('vitalz')){
            $vitalz = $this->sanitizeInput($request->input('vitalz'));
            $reading = $this->sanitizeInput($request->input('vital_reading'));
            $si_unit = $this->sanitizeInput($request->input('si_unit'));

            if (intval($vitalz) === 2) {
                $bp = Functions::parse_blood_pressure($reading);
                if ($bp === null) {
                    $a_type = "warning";
                    $a_message = "Please enter blood pressure in the format systolic/diastolic (e.g., 120/80).";
                    redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
                }
            }
             
            Functions::save_vital_reading($uid, $vitalz, $reading, $si_unit);
            $a_type="success";
            $a_message="Reading saved successfully!";
             redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //approve affliate request
         if($request->input('approve_affliate')){
            $req_id = $this->sanitizeInput($request->input('approve_affliate'));
             
            Functions::patient_approve_request($uid, $req_id);
            $a_type="success";
            $a_message="Request Approved!";
             redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //decline affliate request
         if($request->input('decline_affliate')){
            $req_id = $this->sanitizeInput($request->input('decline_affliate'));
             
            Functions::patient_decline_request($uid, $req_id);
            $a_type="warning";
            $a_message="Request Declined!";
             redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //book appointment
         if($request->input('appointment_time')){
            $doctor = $this->sanitizeInput($request->input('doctor'));
            $appointment_time = $this->sanitizeInput($request->input('appointment_time'));
            $appointment_date = $this->sanitizeInput($request->input('appointment_date'));
            $channel = $this->sanitizeInput($request->input('channel'));
             $symptoms = $request->input('symptoms');
             $all_symptoms ="";
             
             if(!empty($symptoms[0])){
             foreach($symptoms as $sym){
                 $all_symptoms .= $sym.",";
             }
             }else{
                 $all_symptoms ="none";
             }
                 
            Functions::book_appointment($uid, $doctor, $appointment_date, $appointment_time, $channel, $all_symptoms);
            $a_type="success";
            $a_message="Appointment Booked Successfully!";
              
            $doctor_profile =  DB::select('select * from users WHERE ref_code="'.$doctor.'"');
             $description="You have a pending appointment"; 
            $link = "?pg=appointments";
            Functions::notify($doctor_profile[0]->id, $description, $link);
             
             redirect()->to("/dashboard?pg=appointments&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         //save taken prescription
         if($request->input('prescription_taken')){
            $period_taken = $this->sanitizeInput($request->input('period'));
            $prescriptions_taken = $request->input('prescription_taken');
             
            foreach($prescriptions_taken as $taken){
            Functions::take_medication($uid, $taken, $period_taken);
            }
            $a_type="success";
            $a_message="Prescription records updated";
             redirect()->to("/dashboard?pg=medications&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         // STOREFRONT CART & ORDER MANAGEMENT
         
         // Add to storefront cart
         if($request->input('action') == 'add_to_storefront_cart'){
            $doctor_inventory_id = $this->sanitizeInput($request->input('doctor_inventory_id'));
            $doctor_id = $this->sanitizeInput($request->input('doctor_id'));
            $quantity = $this->sanitizeInput($request->input('quantity'));
            
            // Check if already in cart
            $existing = DB::select('select * from storefront_cart where patient_id='.$uid.' and doctor_inventory_id='.$doctor_inventory_id);
            
            if($existing){
                // Update quantity
                DB::update('update storefront_cart set quantity=quantity+?, updated_at=? where patient_id=? and doctor_inventory_id=?',
                    [$quantity, date('Y-m-d H:i:s'), $uid, $doctor_inventory_id]);
            } else {
                // Insert new
                DB::insert('insert into storefront_cart (patient_id, doctor_id, doctor_inventory_id, quantity, created_at, updated_at) values(?,?,?,?,?,?)',
                    [$uid, $doctor_id, $doctor_inventory_id, $quantity, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            }
            
            $a_type = "success";
            $a_message = "Product added to cart!";
            redirect()->to("/dashboard?pg=doctor-storefront&doctor=".$request->input('doctor_ref')."&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         // Update cart quantity
         if($request->input('action') == 'update_cart_quantity'){
            $cart_id = $this->sanitizeInput($request->input('cart_id'));
            $quantity = $this->sanitizeInput($request->input('quantity'));
            
            DB::update('update storefront_cart set quantity=?, updated_at=? where id=? and patient_id=?',
                [$quantity, date('Y-m-d H:i:s'), $cart_id, $uid]);
            
            redirect()->to("/dashboard?pg=storefront-cart&doctor=".$request->input('doctor'))->send();
         }
         
         // Remove from cart
         if($request->input('action') == 'remove_from_cart'){
            $cart_id = $this->sanitizeInput($request->input('cart_id'));
            
            DB::delete('delete from storefront_cart where id=? and patient_id=?', [$cart_id, $uid]);
            
            redirect()->to("/dashboard?pg=storefront-cart&doctor=".$request->input('doctor'))->send();
         }
         
         // Clear cart
         if($request->input('action') == 'clear_cart'){
            $doctor_id = $this->sanitizeInput($request->input('doctor_id'));
            
            DB::delete('delete from storefront_cart where patient_id=? and doctor_id=?', [$uid, $doctor_id]);
            
            redirect()->to("/dashboard?pg=storefront-cart&doctor=".$request->input('doctor'))->send();
         }
         
         // Place storefront order
         if($request->input('action') == 'place_storefront_order'){
            $doctor_id = $this->sanitizeInput($request->input('doctor_id'));
            $sales_rep_id = $this->sanitizeInput($request->input('sales_rep_id'));
            $total_amount = $this->sanitizeInput($request->input('total_amount'));
            $doctor_commission = $this->sanitizeInput($request->input('doctor_commission'));
            $sales_rep_amount = $this->sanitizeInput($request->input('sales_rep_amount'));
            $delivery_address = $this->sanitizeInput($request->input('delivery_address'));
            $delivery_state = $this->sanitizeInput($request->input('delivery_state'));
            $delivery_city = $this->sanitizeInput($request->input('delivery_city'));
            $delivery_phone = $this->sanitizeInput($request->input('delivery_phone'));
            $notes = $this->sanitizeInput($request->input('notes'));
            
            // Generate order number
            $order_number = 'STO-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Create order
            DB::insert('insert into storefront_orders (order_number, patient_id, doctor_id, sales_rep_id, total_amount, doctor_commission, sales_rep_amount, payment_status, order_status, delivery_address, delivery_state, delivery_city, delivery_phone, notes, created_at, updated_at) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [$order_number, $uid, $doctor_id, $sales_rep_id, $total_amount, $doctor_commission, $sales_rep_amount, 'pending', 'pending', $delivery_address, $delivery_state, $delivery_city, $delivery_phone, $notes, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
            $order_id = DB::getPdo()->lastInsertId();
            
            // Get cart items and create order items
            $cart_items = DB::select('select sc.*, dsi.retail_price, dsi.wholesale_price, md.drug_name from storefront_cart sc inner join doctor_storefront_inventory dsi on sc.doctor_inventory_id = dsi.id inner join marketplace_drugs md on dsi.marketplace_drug_id = md.id where sc.patient_id='.$uid.' and sc.doctor_id='.$doctor_id);
            
            foreach($cart_items as $item){
                $subtotal = $item->retail_price * $item->quantity;
                DB::insert('insert into storefront_order_items (order_id, doctor_inventory_id, drug_name, quantity, unit_price, wholesale_price, subtotal, created_at, updated_at) values(?,?,?,?,?,?,?,?,?)',
                    [$order_id, $item->doctor_inventory_id, $item->drug_name, $item->quantity, $item->retail_price, $item->wholesale_price, $subtotal, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            }
            
            // Create commission record
            DB::insert('insert into sales_rep_commissions (sales_rep_id, order_id, doctor_id, amount, status, created_at, updated_at) values(?,?,?,?,?,?,?)',
                [$sales_rep_id, $order_id, $doctor_id, $sales_rep_amount, 'pending', date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
            // Clear cart
            DB::delete('delete from storefront_cart where patient_id=? and doctor_id=?', [$uid, $doctor_id]);
            
            // Send notifications
            Functions::notify($doctor_id, 'New order received: '.$order_number, '?pg=storefront-orders');
            Functions::notify($sales_rep_id, 'New order to fulfill: '.$order_number, '?pg=orders');
            
            $a_type = "success";
            $a_message = "Order placed successfully! Order #".$order_number;
            redirect()->to("/dashboard?pg=storefront-orders&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         // Cancel order
         if($request->input('action') == 'cancel_order'){
            $order_id = $this->sanitizeInput($request->input('order_id'));
            
            DB::update('update storefront_orders set order_status=?, updated_at=? where id=? and patient_id=?',
                ['cancelled', date('Y-m-d H:i:s'), $order_id, $uid]);
            
            $a_type = "success";
            $a_message = "Order cancelled successfully!";
            redirect()->to("/dashboard?pg=storefront-orders&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         // Submit product reviews
         if($request->input('action') == 'submit_product_reviews'){
            $order_id = $this->sanitizeInput($request->input('order_id'));
            $reviews = $request->input('reviews');
            
            foreach($reviews as $review){
                $doctor_inventory_id = $this->sanitizeInput($review['doctor_inventory_id']);
                $rating = $this->sanitizeInput($review['rating']);
                $review_text = isset($review['review_text']) ? $this->sanitizeInput($review['review_text']) : '';
                
                DB::insert('insert into product_reviews (patient_id, doctor_inventory_id, order_id, rating, review_text, created_at, updated_at) values(?,?,?,?,?,?,?)',
                    [$uid, $doctor_inventory_id, $order_id, $rating, $review_text, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            }
            
            $a_type = "success";
            $a_message = "Thank you for your reviews!";
            redirect()->to("/dashboard?pg=storefront-orders&a_type=".$a_type."&a_message=".$a_message)->send();
         }
         
         
         //upload profile picture
         if($request->file('upload_profile')){
         
			$file = $request->file('upload_profile');
			$target_dir = public_path('../../myaccount.myvitalz.ai/assets/images');
			$filename = $this->imageUpload($file, $target_dir);
		
        
            DB::update('UPDATE users SET photo=? WHERE id=?', [$filename, $uid]);
            
             $a_type="success";
            $a_message = "Profile photo updated successfully!";
            redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
        }
         
         //update profile
         if(isset($request->first_name)){
         $first_name= $this->sanitizeInput($request->first_name);
         $last_name= $this->sanitizeInput($request->last_name);
         $phone= $this->sanitizeInput($request->phone);
         $address= $this->sanitizeInput($request->address);
         $state= $this->sanitizeInput($request->state);
         $country= $this->sanitizeInput($request->country);
            
        
            DB::update('UPDATE users SET first_name=?, last_name=?, phone=?, address=?, state=?, country=?   WHERE id=?', [$first_name, $last_name, $phone, $address, $state, $country, $uid]);
            
             $a_type="success";
            $a_message  = "Profile updated successfully!";
            redirect()->to("/dashboard?pg=".$pg."&a_type=".$a_type."&a_message=".$a_message)->send();
        }
        
         
          
         
         $heart_rate_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 1');
         $blood_pressure_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 2');
         $oxygen_saturation_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 3');
         $stress_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 4');
         $blood_glucose_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 5');
         $lipids_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 6');
         $hba1c_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 7');
         $ihra_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 8');
         $body_temperature_readings =  DB::select('select * from vital_readings WHERE user='.$uid.' AND vitalz = 9');
         
         
          //affilate requests
         $my_requests =  DB::select('select * from patients WHERE user='.$uid.' AND user_approve IS NULL');
         $my_doctors =  DB::select('select * from patients WHERE user='.$uid.' AND doctor_approve=1 AND user_approve=1');
         $my_referrals=  DB::select('select * from users WHERE referral='.$uid);
         $my_hospital =  DB::select('select * from patients WHERE user='.$uid.' AND user_approve =1 AND hospital_approve=1');
         $my_pharmacy =  DB::select('select * from patients WHERE user='.$uid.' AND user_approve =1 AND pharmacy_approve=1');
         
        $request_details = [];
        $doctors_details = [];
        $pharmacy_details = [];
        $hospital_details = [];
         
         foreach($my_requests as $pat){
             if(!empty($pat->doctor)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->doctor);
             array_push($request_details, $user_requests[0]);
                 
             }else if(!empty($pat->pharmacy)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->pharmacy);
             array_push($request_details, $user_requests[0]);
                 
             }else if(!empty($pat->hospital)){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->hospital);
             array_push($request_details, $user_requests[0]);
             }
         }
         
         foreach($my_doctors as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->doctor);
             array_push($doctors_details, $user_requests[0]);
         }
         
         foreach($my_pharmacy as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->pharmacy);
             array_push($pharmacy_details, $user_requests[0]);
         }
         
         foreach($my_hospital as $pat){
             $user_requests =  DB::select('select * from users WHERE id='.$pat->hospital);
             array_push($hospital_details, $user_requests[0]);
         }
        
        switch($pg){
                
            case "profile":  
        return view('patient.dashboard', ['pagename'=>'profile', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break; 
            
            case "messages":
                $all_notifications = DB::select('select * from notifications WHERE (user = ? OR user_id = ?) ORDER BY id DESC', [$uid, $uid]);
                return view('patient.dashboard', [
                    'pagename' => 'patient_messages',
                    'user' => $user,
                    'allvitalz' => $allvitalz,
                    'a_type' => $a_type,
                    'a_message' => $a_message,
                    'notifications' => $my_notifications,
                    'all_notifications' => $all_notifications
                ]);
                break;
            
            case "rx":
                // New: e-prescriptions from Virtual Pharmacy for this patient
                $rx_list = DB::select('
                    SELECT ep.*, u_doctor.name as doctor_name, u_pharmacy.name as pharmacy_name
                    FROM e_prescriptions ep
                    LEFT JOIN users u_doctor ON ep.doctor_id = u_doctor.id
                    LEFT JOIN users u_pharmacy ON ep.pharmacy_id = u_pharmacy.id
                    WHERE ep.patient_id = ?
                    ORDER BY ep.created_at DESC
                ', [$uid]);

                // Attach medications per RX
                foreach ($rx_list as $rx) {
                    $rx->medications = DB::select('SELECT * FROM prescription_medications WHERE prescription_id = ?', [$rx->id]);
                }

                return view('patient.dashboard', [
                    'pagename' => 'patient_rx',
                    'user' => $user,
                    'allvitalz' => $allvitalz,
                    'a_type' => $a_type,
                    'a_message' => $a_message,
                    'notifications' => $my_notifications,
                    'rx_list' => $rx_list
                ]);
                break;
                  
            case "affiliates":  
        return view('patient.dashboard', ['pagename'=>'affiliate_patients', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'my_requests'=>$my_requests, 'my_doctors'=>$my_doctors, 'my_hospital'=>$my_hospital, 'my_pharmacy'=>$my_pharmacy, 'request_details'=>$request_details, 'doctors_details'=>$doctors_details, 'pharmacy_details'=>$pharmacy_details, 'hospital_details'=>$hospital_details, 'notifications'=>$my_notifications, 'public_doctors'=>$public_doctors ]);
                break; 
                
            case "referrals":  
        return view('patient.dashboard', ['pagename'=>'referrals', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications, 'my_referrals'=>$my_referrals ]);
                break; 
                
            case "readings":  
        return view('patient.dashboard', ['pagename'=>'readings', 'user'=>$user, 'allvitalz'=>$allvitalz, 'heart_rate_readings'=>$heart_rate_readings, 'blood_pressure_readings'=>$blood_pressure_readings, 'oxygen_saturation_readings'=>$oxygen_saturation_readings, 'stress_readings'=>$stress_readings, 'blood_glucose_readings'=>$blood_glucose_readings, 'lipids_readings'=>$lipids_readings, 'hba1c_readings'=>$hba1c_readings, 'ihra_readings'=>$ihra_readings, 'body_temperature_readings'=>$body_temperature_readings, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break; 
                
            case "appointments": 
                $appointment_doctor = [];
         
         foreach($pending_appointments as $pat){
             if(!empty($pat->doctor)){
             $appoint_doc_details =  DB::select('select * from users WHERE id='.$pat->doctor);
             array_push($appointment_doctor, $appoint_doc_details[0]);
                 
             }else {
                  $appoint_doc_details =  array();
             array_push($appointment_doctor, $appoint_doc_details);
             }
         }
        return view('patient.dashboard', ['pagename'=>'appointments', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'pending_appointments'=>$pending_appointments, 'appointment_doctor'=>$appointment_doctor, 'doctors_details'=>$doctors_details, 'my_doctors'=>$my_doctors, 'notifications'=>$my_notifications]);
                break; 
                
            case "doctor_page": 
                $doc_ref= $this->sanitizeInput($request->d);
                
            $doctor_profile =  DB::select('select * from users WHERE ref_code="'.$doc_ref.'"');
            $appointment_schedule =  DB::select('select * from appointment_schedule WHERE user='.$doctor_profile[0]->id);
                
        return view('patient.dashboard', ['pagename'=>'doctors_page', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'doctor_profile'=>$doctor_profile, 'appointment_schedule'=>$appointment_schedule[0], 'notifications'=>$my_notifications ]);
                break; 
                
            case "medications":  
                $taken_prescription= array();
                $prescription_doctor= array();
                $prescription_last_taken= array();
                $prescription_taken_today= array();
                
                foreach($medications as $med){
                $med_prescriptions =  DB::select('select * from prescriptions WHERE id='.$med->prescription_id);
                    $taken_prescription[]=$med_prescriptions[0];
                }
                
                foreach($prescriptions as $pres){
                $med_doc =  DB::select('select * from users WHERE id='.$pres->doctor);
                    $prescription_doctor[]=$med_doc[0];
                }
                
                foreach($prescriptions as $pres){
                $med_last_taken =  DB::select('select * from medications WHERE prescription_id='.$pres->id);
                    if(!empty($med_last_taken)){
                    $last_med = $med_last_taken[count($med_last_taken)-1];
                    $prescription_last_taken[] = $last_med;
                    
                    $med_time = date("d-m-y", strtotime($last_med->date));
                        if($med_time == date("d-m-y")){
                          $prescription_taken_today[]=1;  
                        }else{
                             $prescription_taken_today[]=0;
                        }
                    }else{
                        $prescription_last_taken[] = array();
                        $prescription_taken_today[]=0;
                    }
                }
                
               
        return view('patient.dashboard', ['pagename'=>'medications', 'user'=>$user, 'allvitalz'=>$allvitalz,  'a_type'=>$a_type, 'a_message'=>$a_message, 'prescriptions'=>$prescriptions, 'medications'=>$medications, 'notifications'=>$my_notifications, 'taken_prescription'=>$taken_prescription, "prescription_doctor"=> $prescription_doctor, "prescription_last_taken"=> $prescription_last_taken, "prescription_taken_today"=> $prescription_taken_today]);
                break; 
                
                
            case "support":  
        return view('/dashboard_doctor', ['pagename'=>'support', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'my_tickets'=>$my_tickets, 'notifications'=>$my_notifications ]);
                break; 
                
                
            case "support_details": 
                $tkid = $this->sanitizeInput($request->input('tkid'));
                
            $my_tickets =  DB::select('select * from support WHERE ticket_id="'.$tkid.'"'); 
            $my_tickets_replies =  DB::select('select * from support_replies WHERE support_id="'.$tkid.'"'); 
            
                
        return view('/dashboard_doctor', ['pagename'=>'support_details', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'my_tickets'=>$my_tickets, 'my_tickets_replies'=>$my_tickets_replies, 'notifications'=>$my_notifications ]);
                break;
                
            case "shop": 
                
                $all_products=  DB::select('select * from products WHERE hide IS NULL');
                shuffle($all_products);
                 $product_ref = array();
         
         foreach($all_products as $pat){
             if($pat->product_ref==NULL){
                 $product_ref[]= array();
             }else{
             $prdt =  DB::select('select * from products WHERE id='.$pat->product_ref);
             $product_ref[] = $prdt[0];
             }
         }
        
                
        return view('patient.dashboard', ['pagename'=>'shop', 'user'=>$user, 'all_products'=>$all_products, 'allvitalz'=>$allvitalz, 'product_ref'=>$product_ref, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break; 
              
            case "checkout": 
                
                $cart = new Cart();
        
        $rtn = $cart->DisplayCart();
         
        
                
        return view('patient.dashboard', ['pagename'=>'checkout', 'user'=>$user, 'cart'=>$rtn, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break; 
              
            // Doctor Storefronts (virtual pharmacies from managing doctors)
            case "storefronts":
                return view('patient.dashboard', ['pagename'=>'patient_storefronts_list', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
            
            // Single doctor storefront detail
            case "doctor-storefront":
                return view('patient.dashboard', ['pagename'=>'patient_doctor_storefront', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
            
            // Storefront cart
            case "storefront-cart":
                return view('patient.dashboard', ['pagename'=>'patient_storefront_cart', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
            
            // Storefront checkout
            case "storefront-checkout":
                return view('patient.dashboard', ['pagename'=>'patient_storefront_checkout', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
            
            // My orders from doctor storefronts
            case "storefront-orders":
                return view('patient.dashboard', ['pagename'=>'patient_storefront_orders', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
            
            // Communities
            case "communities":
                return view('patient.dashboard', ['pagename'=>'patient_communities', 'user'=>$user, 'allvitalz'=>$allvitalz, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
                break;
                
            default:
                 return view('patient.dashboard', ['pagename'=>'overview', 'user'=>$user, 'allvitalz'=>$allvitalz, 'heart_rate_readings'=>$heart_rate_readings, 'blood_pressure_readings'=>$blood_pressure_readings, 'oxygen_saturation_readings'=>$oxygen_saturation_readings, 'stress_readings'=>$stress_readings, 'blood_glucose_readings'=>$blood_glucose_readings, 'lipids_readings'=>$lipids_readings, 'hba1c_readings'=>$hba1c_readings, 'ihra_readings'=>$ihra_readings, 'body_temperature_readings'=>$body_temperature_readings, 'a_type'=>$a_type, 'a_message'=>$a_message, 'notifications'=>$my_notifications ]);
        }
     }
       
  
    public function addToCart(Request $request){
        
        $pd = $this->sanitizeInput($request->pd);
        $qty = $this->sanitizeInput($request->qty);
        $cart = new Cart();
        
        $rtn = $cart->AddProduct($pd, $qty);
        
        return $rtn;
        
    }
    
    public function removeFromCart(Request $request){
        
        $pd = $this->sanitizeInput($request->pd);
        
        $cart = new Cart();
        
        $rtn = $cart->RemoveProduct($pd);
        
        return $rtn;
        
    }
  
    public function updateCart(Request $request){
        
        $pd = $this->sanitizeInput($request->pd);
        $qty = $this->sanitizeInput($request->qty);
        
        $cart = new Cart();
        
        $rtn = $cart->updatecart($pd, $qty);
        
        return $rtn;
        
    }
  
    public function displayCart(Request $request){
        
        $cart = new Cart();
        
        $rtn = $cart->DisplayCart();
        
        if(isset($request->address) && $request->isMethod('post')){
            
             if(Cookie::get('uid')==""){
           redirect()->to("/login")->send();
        }else{
            $uid = Cookie::get('uid');
            $user =  DB::select('select * from users WHERE id='.$uid);
        }
        
         
         $address= $this->sanitizeInput($request->address);
         $lga= $this->sanitizeInput($request->lga);
         $state= $this->sanitizeInput($request->state);
         $phone= $this->sanitizeInput($request->phone);
        $dt = date("d-M-Y");
            $order_id= mt_rand(1000000, 9999999);
            $total =0;
            
            $cart = Cookie::get('cartno');
    		
    		$product = json_decode($cart);
    		
				for($i=0; $i<count($product); $i++)
    		{
    				$pd = new Products($product[$i][0]);
                    $total += ($product[$i][1]*$pd->partPrice());
                    
                     DB::insert('insert into order_products (order_id, product, quantity, amount) values(?,?,?,?)',[$order_id, $product[$i][0], $product[$i][1], $pd->partPrice()]);
    				
    			}	
    			
    		
        
             DB::insert('insert into orders (user, order_id, status, amount, address, lga, state, phone, date) values(?,?,?,?,?,?,?,?,?)',[$uid, $order_id, "pending", $total, $address, $lga, $state,  $phone, $dt]);
            
            $cart = new Cart();
        
        $rtn = $cart->emptyCart();
            
            $callback_url = "http://localhost:8000/shopping-cart?order_id=".$order_id;
$paystack = new Paystack($this->secret_key);
// the code below throws an exception if there was a problem completing the request, 
// else returns an object created from the json response
$trx1 = $paystack->initialize($total*100, $user[0]->email, $callback_url);
$trx = json_decode($trx1);
             
             //log payment
 $this->save_payment($user[0]->id, $trx->data->reference, $total, $order_id, "product");
             
// Get the user to click link to start payment or simply redirect to the url generated
$paylink=$trx->data->authorization_url;
				  redirect()->to($paylink)->send();	
            
                     
               //    redirect()->to("/login?reg=1")->send();
               }
           
        
            
        if($request->input('reference')){
             $chkpay = $this->check_payment($request->input('reference'));
             
             if($chkpay!=1){
                 $paystack = new Paystack($this->secret_key);
             $vfy1 = $paystack->verify_transaction($request->input('reference'));
             $vfy = json_decode($vfy1);
             $pstatus = $vfy->data->status;
             $paid = 0;
             $order_id = $this->sanitizeInput($request->order_id);
				 
				
             if($pstatus=="success"){
                 $paid = 1;
                 $pamt = $vfy->data->amount/100;
                 
                 DB::update('update orders set paid=?, status=? WHERE order_id = ?',['1', 'paid', $order_id]);
					/*
                   $emaildesc = "You paid in N".number_format($vfy->data->amount/100,2)." into ".$package_name." (".$subdetails[0]->name.")";
                           
                    $snderemail = $user[0]->email; 
                 
                 
                      $title ="Deposit into ".$package_name;
                    $description='<table>
                     <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Ref. ID:</td>
               <td>'.$ref_id.'</td>
               </tr>
            <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Amount:</td>
               <td>N'.number_format($vfy->data->amount/100,2).'</td>
               </tr>
            <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Description:</td>
               <td>'.$desc.'</td>
               </tr>
            <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Transaction Type:</td>
               <td>Credit </td>
               </tr>
             <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Payment Method:</td>
               <td>Card </td>
               </tr>
            <tr style="border-bottom: 1px solid #e6e6e6;">
               <td>Beneficiary:</td>
               <td>'.$user[0]->fullname.'</td>
               </tr>
            </table>';
                       
	 $notdata = array('description'=>$description, 'title'=>$title, 'name'=>$user[0]->first_name);
				$blade ="notification_email_content";
		   $subject = "Savings notification";
		   Settings::html_email($notdata, $snderemail,  $subject, $blade);
           */
				       
					   $swal_type="success";
		$swal_message="Payment successful!";
			redirect()->to("http://localhost:8000/payconfirm?swal_message=".$swal_message."&swal_type=".$swal_type)->send();
             }else{
				 
		$swal_type="warning";
		$swal_message=$pstatus;
			 }
             $this->update_payment($request->input('reference'), $pstatus, $paid);
             }
             
			redirect()->to($callback_url."&swal_message=".$swal_message."&swal_type=".$swal_type)->send();
         }
		
        
        return view('checkout', ['cart'=>$rtn]);
        
    }
    
    public function showCart(Request $request){
        
        $cart = new Cart();
        
        $rtn = $cart->DisplayCart();
        
        return $rtn;
        
    }
    
    function search_doctors(Request $request){
        if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pname')){
             $pname = $this->sanitizeInput($request->input('pname'));

            $user =  DB::select('select * from users WHERE doctor=1 AND hospital IS NULL AND pharmacy IS NULL AND phone="'.$pname.'"');
            
            $list = '<ul class="list-unstyled mb-0">';
            for($i=0; $i<count($user); $i++){
                $my_requests = DB::select('select * from patients WHERE (user='.$uid.' AND doctor='.$user[$i]->id.' AND doctor_approve!=2 AND  user_approve =1) OR (patient='.$uid.' AND user='.$user[$i]->id.' AND doctor_approve=1 AND user_approve!=2)');
                
                if($user[0]->photo!=""){
                 $pic = "../assets/images/".$user[$i]->photo;
                }else{ 
                    $pic = "../assets/img/avatars/user.png"; 
                    } 
                
                $list .= ' <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="'.$pic.'" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2">'.$user[$i]->first_name.' '.$user[$i]->last_name.'</h6>
                    </div>
                  </div>
                  <div class="ms-auto" id="btn_'.$user[$i]->ref_code.'">';
                if(empty($my_requests)){
                  $list .= '  <button class="btn btn-label-primary btn-icon btn-sm"  onclick="add_doctor(\''.$user[$i]->ref_code.'\', \'btn_'.$user[$i]->ref_code.'\')"><i class="bx bx-plus"></i> Add</button>';
                }else{
                    $list .= 'Request sent';
                }
                $list .= '
                  </div>
                </div>
              </li>';
            }
            $list .='</ul>';
            
            if(empty($user)){
            $list = "No Doctor found!";
            }
            
            return $list;
         }
    }
    
    function public_doctors(Request $request){
        if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        $public_doctors=  DB::select('select * from users WHERE public=1 AND doctor=1');
        shuffle($public_doctors);
            
            $list = '<ul class="list-unstyled mb-0">';
            for($i=0; $i<count($public_doctors); $i++){
                $my_requests = DB::select('select * from patients WHERE (user='.$uid.' AND doctor='.$public_doctors[$i]->id.' AND doctor_approve!=2 AND user_approve =1) OR (patient='.$uid.' AND user='.$public_doctors[$i]->id.' AND doctor_approve=1 AND user_approve!=2)');
                
                if($public_doctors[0]->photo!=""){
                 $pic = "../assets/images/".$public_doctors[$i]->photo;
                }else{ 
                    $pic = "../assets/img/avatars/user.png"; 
                    } 
                
                $list .= ' <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="d-flex align-items-start">
                    <div class="avatar me-3">
                      <img src="'.$pic.'" alt="Avatar" class="rounded-circle">
                    </div>
                    <div class="me-2">
                      <h6 class="mt-2">'.$public_doctors[$i]->first_name.' '.$public_doctors[$i]->last_name.'</h6>
                    </div>
                  </div>
                  <div class="ms-auto" id="btn_'.$public_doctors[$i]->ref_code.'">';
                if(empty($my_requests)){
                  $list .= '  <button class="btn btn-label-primary btn-icon btn-sm"  onclick="add_doctor(\''.$public_doctors[$i]->ref_code.'\', \'btn_'.$public_doctors[$i]->ref_code.'\')"><i class="bx bx-plus"></i> Add</button>';
                }else{
                    $list .= 'Request sent';
                }
                $list .= '
                  </div>
                </div>
              </li>';
                
                if($i >9){
                    break;
                }
            }
            $list .='</ul>';
            
            if(empty($public_doctors)){
            $list = "";
            }
            
            return $list;
         
    }
    
    function add_doctors(Request $request){
         if(Cookie::get('uid')==""){
           exit();
        }else{
            $uid = Cookie::get('uid');
         }
        
        if($request->input('pcode')){
            $ref_code = $this->sanitizeInput($request->input('pcode'));
            $doctor =  DB::select('select * from users WHERE ref_code="'.$ref_code.'"');
            Functions::add_doctor($uid, $doctor[0]->id);
            $description="You have a new patient request"; 
            $link = "?pg=affiliates";
            Functions::notify($doctor[0]->id, $description, $link);
            
        }
    }
     
    
    function get_appointment_intervals(Request $request){
         $doc_ref= $this->sanitizeInput($request->doc_ref);
                
        $doctor_profile =  DB::select('select * from users WHERE ref_code="'.$doc_ref.'"');
        $appointment_schedule =  DB::select('select * from appointment_schedule WHERE user='.$doctor_profile[0]->id);
        
        $appoint_date = $this->sanitizeInput($request->input('appointment_date'));
        $day_of_the_week = date("D", strtotime($appoint_date));
            
        switch($day_of_the_week){
            case "Mon":
                $start_time = $appointment_schedule[0]->monday_start;
                $end_time = $appointment_schedule[0]->monday_end;
                break;
                
            case "Tue":
                $start_time = $appointment_schedule[0]->tuesday_start;
                $end_time = $appointment_schedule[0]->tuesday_end;
                break;
            
            case "Wed":
                $start_time = $appointment_schedule[0]->wednesday_start;
                $end_time = $appointment_schedule[0]->wednesday_end;
                break;
            
            case "Thu":
                $start_time = $appointment_schedule[0]->thursday_start;
                $end_time = $appointment_schedule[0]->thursday_end;
                break;
            
            case "Fri":
                $start_time = $appointment_schedule[0]->friday_start;
                $end_time = $appointment_schedule[0]->friday_end;
                break;
            
            case "Sat":
                $start_time = $appointment_schedule[0]->saturday_start;
                $end_time = $appointment_schedule[0]->saturday_end;
                break;
            
            case "Sun":
                $start_time = $appointment_schedule[0]->sunday_start;
                $end_time = $appointment_schedule[0]->sunday_end;
                break;
            
        }
        
        $time_intval = "<option value=''>--</option>";
        
        for($i=0; $i<24; $i++){
            $add_start = strtotime("+".$i." hour", strtotime($start_time));
            $check_end = strtotime($end_time);
            
            $check_current_time = date("h:i a", $add_start);
            $book_time = strtotime($appoint_date." ".$check_current_time);
            
            $check_booking =  DB::select('select * from appointments WHERE start_time ='.$book_time.' AND doctor='.$doctor_profile[0]->id);
            
            if($add_start<=$check_end && empty($check_booking[0]) && $start_time!=""){
                 $time_intval .= "<option value='".date("h:i a", $add_start)."'>".date("h:i a", $add_start)."</option>";
            }
        }
        
       return $time_intval;
    }
     
    
    
    function get_si_units(Request $request){
        $si_units="";
        
        if($request->input('vitalz')){
            $vitalz = $this->sanitizeInput($request->input('vitalz'));
            $si_units=Functions::get_si_units($vitalz);
        }
        
        return $si_units;
    }
       
      
	// function to upload images
function imageUpload($file, $target_dir){
  
 // Allowed extensions
 $extensions_arr = array("jpg","jpeg","png","gif");
$newfilename="";
 // Check extension
 if( in_array($file->getClientOriginalExtension(),$extensions_arr) ){
 
 
$newfilename = round(microtime(true)) .mt_rand(1, 999).'.' . $file->getClientOriginalExtension();
  
  // Upload file
  $file->move($target_dir, $newfilename);

	}
	return $newfilename;
}
	
	// function to upload images
function imageUploadDoc($file, $target_dir){
  
 // Allowed extensions
 $extensions_arr = array("jpg","jpeg","png","gif","doc", "docx", "pdf");
$newfilename="";
 // Check extension
 if( in_array($file->getClientOriginalExtension(),$extensions_arr) ){
 
 
$newfilename = round(microtime(true)) .mt_rand(1, 999).'.' . $file->getClientOriginalExtension();
  
  // Upload file
  $file->move($target_dir, $newfilename);

	}
	return $newfilename;
}
	
  
    	
function sanitizeInput($input){
	//sanitze an input
	//include("connect.php");
	$input = strip_tags(htmlspecialchars(trim($input)));
	//$input = mysqli_real_escape_string($link, $input);
	return $input;
}
    
    
function curPageURL() {
 $pageURL = 'http';
// if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
    
    //../../myaccount.myvitalz.ai/assets/images
}
