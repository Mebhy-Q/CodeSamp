<?php
//populate dropdown form filed with list of users function
function populate_users( $form ) {
    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'populate-users' ) === false ) {
            continue;
        }
        //we dont want admins, or deactivated, ect
        $users = get_users( [ 'role__not_in' => [ 'Administrator','demo','deactivated','noadmin' ] ] );
        $choices = array();
        foreach ( $users as $user ) {
			//get this profile data for every resulting user
            $choices[] = array( 'text' => $user->display_name, 'value' => $user->id );
        }
        // update 'Select a Person' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select a Person';
        $field->choices = $choices;
    }
    return $form;
}
//populate filed with list of users function
function populate_users_display( $form ) {
    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'populate-users-display' ) === false ) {
            continue;
        }        
        $users = get_users( [ 'role__not_in' => [ 'Administrator','demo','deactivated','noadmin'  ] ] );
        $choices = array();
        foreach ( $users as $user ) {
            $choices[] = array( 'text' => $user->display_name, 'value' => $user->display_name );
        }
        // update 'Select a Person' to whatever you'd like the instructive option to be
        $field->placeholder = 'Select a Person';
        $field->choices = $choices;
    }
    return $form;
}


//populate dropdown with items filed from all unique previous database skills functions
function populate_skills( $form ) {

    if ( is_array( $form ) || is_object( $form ) ) {

        foreach ( $form['fields'] as &$field ) {  // for all form fields
            $field_id = $field['id'];	
            if ( 'list' == $field->get_input_type() ) {
                $has_columns = is_array( $field->choices );
                if ( $has_columns ) {
					//call wp database					
                    foreach ( $field->choices as $key => &$choice ) { // for each column
                        $isDropDown = rgar( $choice, 'isDropDown' );
                        $column = rgars( $field->choices, "{$key}/text" );
                        if ( $isDropDown && 'Company' == $column ) {
							global $wpdb;
							//SQL QUERY FOR Options
							$SQL = "SELECT GROUP_CONCAT(DISTINCT wpdatatable_12.company) as 'group' FROM wpdatatable_12";
   							$result = $wpdb->get_results($SQL);
							//$choices = 'Type to search. Return to add,';	
							$choices = $result[0]->group;
							$result=null;
							//add default list ", Option 1, Option 2, Option 3"
                            						
                            $choice['isDropDownChoices'] = $choices;  
                        }elseif ( $isDropDown && 'Brand' == $column ) {
							//SQL QUERY FOR unique responces in Brands, start with the defaults
							global $wpdb;
							$SQL = "SELECT GROUP_CONCAT(DISTINCT wpdatatable_12.brand) as 'group' FROM wpdatatable_12";
   							$result = $wpdb->get_results($SQL);
							//$choices = 'Type to search. Return to add,';
							$choices = $result[0]->group;
							$result=null;
							//add default list                            							
                            $choice['isDropDownChoices'] = $choices;
                        }elseif ( $isDropDown && 'Therapeutic Area' == $column ) {
							global $wpdb;
							//SQL QUERY FOR all unique responces in Therapeuthic Area, start with the defaults
							$SQL = "SELECT GROUP_CONCAT(DISTINCT wpdatatable_12.area) as 'group' FROM wpdatatable_12";
   							$result = $wpdb->get_results($SQL);
							$default = "Accountable care organizations,Affordable Care Act,Aging issues,Allergy/asthma,Attention deficit hyperactivity disorder (ADHD),Bipolar disorder,Cardiovascular diseases,COPD,Cosmetic surgery,Depression,Diabetes,Electronic medical records,Empowering patients,Fibromyalgia,Healthcare financing issues,Healthcare reform,Health information technology,Health literacy,HIV/AIDS,Hypertension,Insomnia,Lupus,Managed care,Medicaid,Medical homes,Medicare,Mobile health (mHealth),Multiple sclerosis,Nutrition,Oncology,Pain management,Patient-centered care,Reproductive medicine,Rheumatoid arthritis,Schizophrenia,Stroke,Urological issues,Value-based purchasing,Women’s health";
							$group = $result[0]->group;
							//$choices = "";
							//Create arrays from our string results
							$array1= explode(',',$default);
							$array2= explode(',',$group);
							//merge it all
							$final_array = array_merge(array_diff($array2,$array1),$array1);
							//Sort
							asort($final_array);
							$choices = implode(",", $final_array);
							//add query result to dropdowns 
							$final_array = array_keys(array_flip(explode(',', $choices)));
							asort($final_array);
							$choices = implode(",", $final_array);
														
                            $choice['isDropDownChoices'] = $choices;
						}
                    }
                }
            }
        }
    }
    return $form;
}


//add/rem emp request
function post_to_addrem($entry, $form) {
	//define varrs
	$timestamp = rgar($entry, 'date_created' );	
	//submission type Addition
		if(rgar($entry, '1' )=="Addition"){
			//if emp add has a prefered name
			if (strlen(rgar($entry, '8' )) <= 2){
				$employee = rgar($entry, '6.3' )." ".rgar($entry, '6.6' );
			}else{
				// person has a prefered name
				$employee = rgar($entry, '8' )." ".rgar($entry, '6.6' );
			}	
			//start date
			$date = rgar($entry, '7' );
			$submissiontype = rgar($entry, '1' )." - ".rgar($entry, '13' );
			
		}else{
			//emp seperation
			$emp = rgar($entry, '3' );
			//end date
			$date = rgar($entry, '4' );
			$submissiontype = rgar($entry, '1' );
			//who is it because our submission submits an id for longetivty, this is better than lookups every time we build the table
			$SQL = "SELECT users.display_name FROM users WHERE users.ID = ".$emp;
   				$result = $wpdb->get_results($SQL);
				$employee = $result[0]->display_name;
		}
		
		$dept = rgar($entry, '10' );
		$super = rgar($entry, '9' );
		//Clean the text entry from anything that could cause problems
		$location = clean(rgar($entry, '11' ));
		$title = clean(rgar($entry, '12' ));
		$office = clean(rgar($entry, '18' ));
		$cell = clean(rgar($entry, '19' ));
	
	$SQL = "INSERT INTO wpdatatable_5 ( submissiontype, timestamp, name, startdate, title, dept, supervisor, location, ext, cell ) VALUES ( '$submissiontype', '$timestamp', '$employee', '$date', '$title', '$dept', '$super', '$location', '$office', '$cell')";
   	$wpdb->query($SQL);		

}


