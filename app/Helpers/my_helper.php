<?php 


function warn_msg($param){
    return '
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Warning!</strong> '.$param.'
        </div>
    ';
}

function succ_msg($param){
    return '
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Success!</strong> '.$param.'
        </div>
    ';
}

function err_msg($param){
    return '
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <strong>Error!</strong> '.$param.'
        </div>
    ';
}

function url_template_client()
{
    return base_url().'public/home/';
}

function cut_words($sentence,$word_count){
    $space_count = 0;
    $print_string = '';
    $last_string = '';
    for($i=0;$i<strlen($sentence);$i++){
        if($sentence[$i]==' ')
        $space_count ++;
        $print_string .= $sentence[$i];
        if($space_count == $word_count){
            $last_string= '...';
            break;
        }
    }
    
    echo preg_replace('/<img[^>]+./','',$print_string.$last_string);
}

function mix_word($value='')
{
    $word = explode(' ', $value);

    $word_mix = implode('_', $word);

    return $word_mix;
}

function TanggalIndo($date){
    $BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des");
 
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
 
    $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;        
    return($result);
}

function date_time($date){
    $explode = explode(' ', $date);
    $tanggal = $explode[0];
    $waktu = $explode[1];

    $BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des");
 
    $tahun = substr($tanggal, 0, 4);
    $bulan = substr($tanggal, 5, 2);
    $tgl   = substr($tanggal, 8, 2);
 
    $result_date = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;

    $result = $result_date." ".$waktu ;
    
    return($result);
}

function date_time_2($date){
    $explode = explode(' ', $date);
    $tanggal = $explode[0];
    $waktu = $explode[1];

    $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
    $tahun = substr($tanggal, 0, 4);
    $bulan = substr($tanggal, 5, 2);
    $tgl   = substr($tanggal, 8, 2);
 
    $result_date = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;

    $result = $result_date." ".$waktu ;
    
    return($result);
}

function format_time($time){
    $explode = explode(' ', $time);
    $tanggal = $explode[0];
    $waktu = $explode[1];
    
    return($waktu);
}

function date_time_metronic($date){
    $explode = explode(' ', $date);
    $tanggal = $explode[0];
    $bulan = $explode[1];
    $tahun = $explode[2];
    $jam = $explode[4];
    
    $BulanIndo = array("","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $hitung = count($BulanIndo);
    for ($i = 1 ; $i <= $hitung ; $i++)
    {
        ($bulan == $BulanIndo[$i]) ? $bulan = $i : "";
    }
    
    $result_date = $tahun."-".$bulan."-".$tanggal." ".$jam;
    $result = $result_date ;
    
    return($result);
}

function BulanIndo($date){
    $BulanIndo = array("","Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 
    $result = $BulanIndo[(int)$date];        
    return($result);
}

function format_rupiah($angka)
{
    $jadi = "Rp " . number_format((double)$angka,2,',','.');
    return $jadi;
} 
function format_harga($angka)
{
    $jadi = number_format((double)$angka,0,',','.');
    return $jadi;
}

function format_number($input){
    $input = number_format($input);
    $input_count = substr_count($input, ',');
    if($input_count != '0'){
        if($input_count == '1'){
            return substr($input, 0, -4).'RB';
        } else if($input_count == '2'){
            return substr($input, 0, -8).'JT';
        } else if($input_count == '3'){
            return substr($input, 0,  -12).'M';
        } else {
            return;
        }
    } else {
        return $input;
    }
}

function curURL()
{
    $pageURL = 'http';
    if (@$_SERVER['HTTPS'] == 'on') {
        $pageURL .= 's';
    }
    
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    
    return $pageURL;
}

function curl($url) {
    $data = file_get_contents($url);
    return $data;
}

function curCname()
{
    $CI =& get_instance();
    $url = '';
    $url = $CI->router->fetch_directory().$CI->router->fetch_class();
    $url = strtolower($url);
    return $url;
}

function changeDateFormat($format,$date)
{
    switch($format)
    {
        case "database":
            return date('Y-m-d',strtotime($date));
            break;
        case "webview":
            return date('d-m-Y',strtotime($date));
            break;
        case "datepicker":
            return date('d/m/Y',strtotime($date));
            break;
    }	
}

function paging($url, $total, $perpage=NULL)
{
    $ci =& get_instance();
    $ci->load->library('Mypagination');

    $config['base_url'] = @$url;
    $config['num_links'] = 3;
    $config['total_rows'] = @$total;
    $config['per_page'] = @$perpage ? $perpage : 5;

    $config['full_tag_open'] = '<div id="paging" style="margin: 0px 0px 0px 15px;"><ul class="pagination">';
    $config['full_tag_close'] = '</ul></div>';

    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';

    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';

    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $ci->mypagination->initialize($config);

    return $ci->mypagination->create_links();
}

function uri($param)
{
    $ci =& get_instance();
    return $ci->uri->segment($param);
}

function contact($col){
  $CI =& get_instance();
  $mod = $CI->load->model('admin/m_info','minfo');

  $data_profil = $mod->minfo->get_info();

  return @$data_profil[0]->$col;
}

function align_right($max,$nilai)
{
    $length = strlen($nilai);
    $sisa = $max - $length;

    $hasil="";
    for($i=0;$i<$sisa;$i++){
        $hasil.=" ";
    }
    $hasil.=$nilai;
    return $hasil;
}
function align_left($max,$nilai)
{
    $length = strlen($nilai);
    $sisa = $max - $length;
    $spasi = repeat_value($sisa,' ');
    return $nilai.$spasi;
}
function align_right_strip($max,$nilai)
{
    $length = strlen($nilai);
    $sisa = $max - $length;

    $hasil="";
    for($i=0;$i<$sisa;$i++){
        $hasil.="-";
    }
    $hasil.=$nilai;
    return $hasil;
}
function align_center($max,$nilai)
{
    // $length = strlen($nilai);
    // $stgh_length = $length / 2;
    // $stgh_max = (int)$max / 2;
    // $sisa = $stgh_max - $stgh_length;

    // $hasil="";
    // for($i=0;$i<$sisa;$i++){
    //     $hasil.=" ";
    // }
    // $hasil.=$nilai;
    // return $hasil;

    $length = strlen($nilai);
    $sisa = $max - $length;
    $spasi_awal = floor($sisa/2);
    $spasi_akhir = $sisa - $spasi_awal;

    return repeat_value($spasi_awal,' ').$nilai.repeat_value($spasi_akhir,' ');
}
function Terbilang($satuan){
    $huruf = array("","Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan","Sepuluh","Sebelas");

    if($satuan < 12)
        return " " . $huruf[$satuan];
    else if($satuan < 20)
        return Terbilang($satuan - 10) . " Belas";
    else if($satuan < 100)
        return Terbilang($satuan / 10) . " Puluh" . Terbilang($satuan % 10);
    else if($satuan < 200)
        return " Seratus" . Terbilang($satuan - 100);
    else if($satuan < 1000)
        return Terbilang($satuan / 100) . " Ratus" . Terbilang($satuan % 100);
    else if($satuan < 2000)
        return " Seribu" . Terbilang($satuan - 1000);
    else if($satuan < 1000000)
        return Terbilang($satuan / 1000) . " Ribu" . Terbilang($satuan % 1000);
    else if($satuan < 1000000000)
        return Terbilang($satuan / 100000000) . " Juta" . Terbilang($satuan % 1000000);
    else if($satuan >= 1000000000)
        echo "Hasil terbilang tidak dapat diproses karena nilai terlalu besar !";
} 
function cetak_garis($jumlah)
{
    $hasil="";
    for($i=0;$i<$jumlah;$i++){
        $hasil.="-";
    }
    return $hasil;
}
function repeat_value($jumlah,$delimiter){
    $hasil = '';
    for($i=0;$i<$jumlah;$i++){
        $hasil .= $delimiter;
    }
    return $hasil;
}


function save_as($file, $file_name, $file_size, $folder, $flag, $size)
{
    $ret['error'] = 0;

    if ($file == "none") :
        $ret['error'] = 1;
        $ret['msg'] = "Please, Fill file field...";
        return $ret;
        exit();
    endif;

    if ($flag) :
        if ($file_size >= $size*1024) :
            $ret['error'] = 1;
            $ret['msg'] = "File size too large. Maximum file size $size KB...";
            return $ret;
            exit();
        endif;
    endif;

    $name_file = time()." - ".$file_name;
    if (!@copy ($file,$folder."/".$name_file)) :
        $ret['error'] = 1;
        $ret['msg'] = "Copy file failed. Please check the file...";
        return $ret;
        exit();
    endif;

    $ret['nama_file'] = $name_file;
    return $ret;
    exit();
}

function decrease_arrnull($param)
{
    $arr = array();
    foreach ($param as $key => $val) {
        if(!empty($val)){
            $arr[$key] = $val;
        }
    }

    return $arr;
}

function complete_zero($number,$max_length)
{
   $number_length = strlen($number);
   $zero_length = $max_length - $number_length;
   $zero = "";
   for($i=1;$i<=$zero_length;$i++)
   {
      $zero .= '0';
   }
   return $zero.$number;
}

function complete_zero_after($number,$max_length)
{
   $number_length = strlen($number);
   $zero_length = $max_length - $number_length;
   $zero = "";
   for($i=1;$i<=$zero_length;$i++)
   {
      $zero .= '0';
   }
   return $number.$zero;
}

function space($num)
{
    $space = '';
    for($i=1;$i<=$num;$i++)
    {
        $space .= '&nbsp;&nbsp;&nbsp;';
    }
    return $space;
}

function unit_acuan($satuan)
{
    $CI =& get_instance();

    $CI->db->where('id', $satuan);
    $query = $CI->db->get('atombizz_converter');
    $result = $query->row();

    return $result->acuan;
}

function unit_converter($qty,$satuan)
{
    $CI =& get_instance();

    $CI->db->where('id', $satuan);
    $query = $CI->db->get('atombizz_converter');
    $result = $query->row();

    $data['qty'] = $qty*$result->acuan;

    if($result->kategori!='satuan'){
        $CI->db->where('kategori', $result->kategori);
        $CI->db->where('acuan', 1);
        $CI->db->limit(1);
        $query = $CI->db->get('atombizz_converter');
        $result = $query->row();
        $data['satuan'] = $result->id;
    } else {
        $data['satuan'] = $satuan;
    }

    return json_encode($data);
}

function autocutter($printer='')
{
    $Data = "\n";

    $handle = printer_open($printer); 
    printer_set_option($handle, PRINTER_MODE, "TEXT");
    printer_write($handle, $Data); 
    printer_close($handle);
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
     
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'Linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    }
     
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
     
     
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
    }
          
    $i = count($matches['browser']);
    if ($i != 1) {
         
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
     
    if ($version==null || $version=="") {$version="?";}
     
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

function my_key() 
{
    $CI =& get_instance();
    $CI->load->library('encryption');
    $key = $CI->encryption->initialize(array(
            'cipher' => 'aes-256',
            'mode' => 'ctr'
        )
    );
    return $key;
}

function my_email() 
{
    $CI =& get_instance();
    $config = Array(  
        'protocol' => 'smtp',  
        'smtp_host' => 'ssl://smtp.googlemail.com',  
        'smtp_port' => 465,  
        'smtp_user' => 'nama@gmail.com',   
        'smtp_pass' => 'passwordmu',   
        'mailtype' => 'html',   
        'charset' => 'iso-8859-1' 
    ); 
    $CI->load->library('email', $config);
    $mail = $CI->email->set_newline("\r\n");
    
    return $mail;
}

function my_hash($data) 
{
    $options = [
        'cost' => 12,
    ];
    $result = password_hash($data, PASSWORD_BCRYPT, $options);
    
    return $result;
}

function my_verify_hash($data,$hash) 
{
    if (password_verify($data, $hash)) { 
        $result = 1; 
    } else { 
        $result = 0; 
    } 
        
    return $result;
}

function my_seo($string) {

        $string = trim($string); // Trim String

        $string = strtolower($string); //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )

        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);  //Strip any unwanted characters

        $string = preg_replace("/[\s-]+/", " ", $string); // Clean multiple dashes or whitespaces

        $string = preg_replace("/[\s_]/", "-", $string); //Convert whitespaces and underscore to dash

        return $string;
}

function h_word_wrap($val='-',$length=30) {
    $val_short = substr($val, 0,$length);
    $jum_char  = strlen($val);
    if($jum_char >= $length){ 
        $read_more = "..."; 
        $warna = "color:blue;cursor:pointer;";
    }else{ 
        $read_more = ""; 
        $warna = "";
    }
    $res = '<div class="btn_more" style="'.$warna .'">'.
                '<div class="text_short">'.$val_short.' <span class="btn_more">'.$read_more.'</span>  </div>'.
                '<div class="text_full" style="display:none;">'.wordwrap($val, $length, "<br>").'</div>'.
            '</div>';
    return $res;
}

function h_parsing_float($value) {
    return is_float($value) ? floatval(sprintf('%.2f', $value)) : intval($value);
}

function h_pembagian($val_1=0,$val_2=0){
    if ($val_1 == '') {$val_1 = 0;}
    if ($val_2 == '') {$val_2 = 0;}
    if ($val_2 == 0) {
        if ($val_1 <> '') {
            $divider = $val_1;
        }else {
            $divider =  1;
        }
    }else { 
        $divider = $val_2;
    }
    //apabila nilai total target 0
    if ($val_2 > 0) {
        $percent = ($val_1 / $divider);
    }else{
        $percent = 0;
    }
    // $percent = ($val_1 / $divider) * 100;
    $percent = h_parsing_float($percent);
    return $percent;
}

function h_upload_img($image){
    //API URL
    $url = "https://api.imgbb.com/1/upload";
    //create a new cURL resource
    $ch = curl_init($url);
    if(getenv('MY_UPLOAD_IMAGE_KEY')){
        $key = getenv('MY_UPLOAD_IMAGE_KEY');
    }else{
        $key = "96d7d997fd7063f7948de94db9467e85";
    }
    //setup request to send json via POST
    $data = [
        'key' => $key,
        'image' => $image,
    ];
    // var_dump($data);die;
    $headers = [
        'Content-Type: application/x-www-form-urlencoded',
    ];
    $payload = http_build_query($data);
    //attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute the POST request
    $result = curl_exec($ch);
    if (curl_error($ch)) {
        throw new \Exception(curl_error($ch));
    }
    //close cURL resource
    curl_close($ch);
    return $result;
}

function getIPAddress() {  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  //whether ip is from the share internet  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    }  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //whether ip is from the proxy  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  else {  //whether ip is from the remote address  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}  

function is_image($path)
{
    $a = getimagesize($path);
    $image_type = $a[2];
    
    if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        return true;
    }
    return false;
}