<?php

if (! function_exists('selectRawSlug')) {
    function selectRawSlug(){
      $uri  = "cms_programandoweb";
      return "CONCAT('". $uri ."','/',slug) as slug";
    }
}


if (! function_exists('response_concat_url')) {
    function response_concat_url($column,$alias)
    {
      $uri  = env('APP_URL');
      return "CONCAT('". $uri ."','/',".$column.") as ".$alias;
    }
}

if (! function_exists('url_generator')) {
    function url_generator($prefix, $key="id")
    {
      list($none,$uri)  = explode("api",url()->current());
      list($none,$uri2)  = explode("?",url()->full());

      return  " *,
                CONCAT('". $uri ."','/',".$key.") as edit,
                CONCAT('". $uri ."','/',".$key.") as destroy
              ";
    }
}

if (! function_exists('punctuation_marks')) {
    function punctuation_marks()
    {
      return [
                ",",
                ";",
                ".",
                "¿",
                "?",
                "¡",
                "!",
                "*",
                "@",
                "_",
                "-",
                "#",
              ];
    }
}

if (! function_exists('set_lowercase')) {
    function set_lowercase($fields)
    {
      return $fields;
      $fields_lowercase=[];
      foreach ($fields as $key => $value) {
        $fields_lowercase[$key] = strtolower($value);
      }
      return $fields_lowercase;
    }
}

if (! function_exists('array_clients_columns')) {
    function array_clients_columns($vars)
    {
        $array=[
          "name"=>"Nombres",
          "surnames"=>"Apellidos",
          "gender"=>"Género",
          "marital_status"=>"Estado Civil",
          "document_type"=>"Tipo de documento",
          "identification"=>"Documento",
          "identification_expedition_place"=>"Fecha de expedición del documento",
          "nationality"=>"Nacionalidad",
          "place_birth"=>"Lugar de nacimiento",
          "date_birth"=>"Fecha de nacimiento",
          "dependents"=>"Personas a cargo",
          "direction"=>"Dirección",
          "neighborhood"=>"Barrio",
          "city"=>"Ciudad",
          "department"=>"Departamento",
          "phone"=>"Teléfono fijo",
          "cellphone"=>"Celular",
          "stratum"=>"Estrato",
          "email"=>"Correo electrónico",
          "education_level"=>"Nivel educativo",
          "date_into_employment"=>"Tiempo en el empleo",
          "business"=>"Empresa",
          "position"=>"Cargo",
          "salary"=>"Salario",
          "business_dependence"=>"Negocio o dependencia",
          "discount_amount"=>"Autoriza descontar del sueldo",
          "discount_amount_extra"=>"Porcentaje descuento del sueldo",
          "savings_program"=>"Programa de ahorros",
          "open_account"=>"Abrir cuenta",
          "open_account_amount"=>"Monto de apertura",
          "interest_cinema"=>"Interés en el cine",
          "interest_theater"=>"Interés en el teatro",
          "interest_concerts_shows"=>"Interés en el conciertos",
          "interest_subscriptions"=>"Interés en suscripciones",
          "interest_gym"=>"Interés en el gimnasio",
          "own_home"=>"Vivienda propia",
          "financed_home"=>"Vivienda financiada",
          "financed_home_bank"=>"El banco financiador de la vivienda",
          "own_vehicle"=>"Tiene vehiculo propio",
          "financed_vehicle"=>"Vehículo financiado",
          "financed_vehicle_bank"=>"Banco financiador del vehículo",
          "life_insurance"=>"Tiene seguros de vida",
          "life_insurance_bank"=>"Entidad seguro de vida",
          "funeral_insurance"=>"Desea tomar seguro exequial",
          "have_credit"=>"Tiene créditos",
          "have_credit_amount"=>"Si la respuesta es si en que rango (Millones)",
          "own_business"=>"Tiene algún tipo de negocio propio",
          "would_you_like_business"=>"Le gustaría tenerlo",
          "business_idea"=>"¿Cual es su idea de negocio?",
          "birth_spouse"=>"Fecha de nacimiento del cónyugue",
          "education_level_spouse"=>"Nivel educativo del cónyugue",
          "surnames_spouse"=>"Apellido del cónyugue",
          "spouse_names"=>"Nombres del cónyugue",
          "identification_expedition_date"=>"",
        ];



        $return = [] ;

        foreach ($array as $key => $value) {
          if (!empty($vars[$key]) && $vars[$key]!="null") {
            $return[$value]   =   $vars[$key];
          }
        }
        //p($return);

        return $return;

    }
}

if (! function_exists('p')) {
    function p($var,$exit=true)
    {
        echo '<pre>';
          print_r($var);
        echo '</pre>';
        if ($exit) {
          exit;
        }
    }
}

if (! function_exists('abstractionPost')) {
    function abstractionPost($request){
      $exceptions =   [ "pathname"=>true,
                        "created_at"=>true,
                        "updated_at"=>true,
                        "csrf_token"=>true,
                        "access_token"=>true];
      $return     =   [];
      foreach ($request->input() as $key => $value) {
        if (empty($exceptions[$key])) {
          $return[$key]=$value;
        }
      }
      return $return;
    }
}

if (! function_exists('dateFormatMysql')) {
    function dateFormatMysql($column,$format='%d/%m/%Y %H:%i %p',$alias=false){
      if ($alias) {
        return "DATE_FORMAT(".$column.",'".$format."') as ".$alias;
      }else {
        return "DATE_FORMAT(".$column.",'".$format."') as ".$column."_string";
      }

    }
}

if (! function_exists('selectRawStatus')) {
    function selectRawStatus($object,$column,$group="basic"){
      $object->leftjoin('ma_statuses', $column , '=', 'ma_statuses.value')->where("ma_statuses.group","=",$group);
      $object->selectRaw("ma_statuses.label AS ".str_replace(".","_",$column)."_string");
      return $object;
    }
}
