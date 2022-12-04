# SimpleApiPHP

Penggunaan api sederhana utk cek siswa yg akses internet. 

Penggunaan moodle secara offline (jaringan lokal) masih ada celah jika siswa bisa searching atau bertanya ke teman. Sejauh ini cara mengatasinya adalah 
1. Anti tab dengan deteksi `window.onblur` pada javascript, maka perlu
2. Memastikan javascript aktif
3. Safe Exam Browser
Cara 1 tidak berlaku jika peserta menggunakan split screen/floating window. Metode 3 menurut pengalaman akan menghasilkan banyak masalah jika *diwajibkan* menggunakan laptop. Sehingga bisa membuka dua browser sekaligus. Oleh karena itu, dibuatlah cara prentif lain,yakni mencatat siswa yang terhubung dengan internet, baik utk searching maupun untuk komunikasi / kerjasama

di `/moodle/mod/quiz/renderer.php`  
siapkan 

```php
public function online(){
	    $scripton = 'const checkConnection = async () => {
    try {
        const response = await fetch(
            "https://api.sman81jkt.sch.id/ujian/1"
	); ' ;
	    $scripton .= 'return response.status >= 200 && response.status < 300;
    } catch (error) {
        return false;
    }
};' ;
$scripton .='function lapor(){
var myHeaders = new Headers();
myHeaders.append("COntent-Type","application/x-www-form-urlencoded");
var urlencoded = new URLSearchParams();
const d = new Date();
urlencoded.append("time",d.getHours()+":"+d.getMinutes()+":"+d.getSeconds());
urlencoded.append("nama","'.$_SESSION['USER']->firstname.'-'.$_SESSION['USER']->lastname.'");

var requestOptions = {
	method:"POST",
	headers: myHeaders,
	body: urlencoded,
	redirect: "follow"
};

fetch("https://api.sman81jkt.sch.id/ujian",requestOptions);
//	.then(response => response.text())
//	.then(result => console.log(result))
//	.catch(error => console.log("error",error));

}

const showStatus = document.getElementById("show_status");
setInterval(async () => {
    const isOnline = await checkConnection();
    showStatus.innerText = isOnline ? ":(" : "ok";
     if(isOnline==1){lapor();}
    }, 30000);' ;
	    $output .= html_writer::tag('p','-',array('id'=> 'show_status'));
	   $output .=  html_writer::script($scripton);
	return $output;
}
	
```

di file yg sama, cari fungsi `attempt_page` lalu sisipkan sehingga
```php
public function attempt_page($attemptobj, $page, $accessmanager, $messages, $slots, $id,
            $nextpage) {
        $output = '';
        $output .= $this->header();
        $output .= is_siteadmin() ? $this->during_attempt_tertiary_nav($attemptobj->view_url()) : '';
	$output .= $this->quiz_notices($messages);
	$output .= $this->online(); \\di sini
        $output .= $this->countdown_timer($attemptobj, time());
        $output .= $this->attempt_form($attemptobj, $page, $slots, $id, $nextpage);
        $output .= $this->footer();
        return $output;
    }
```

- Import db_kampus.sql atau sesuaikam
- Ubah koneksi.php sesuaikan dengan host,username,password, dan db anda

sample video
https://youtube.com/shorts/AW4SNSeGmu4?feature=share


thanks to
[rizalrizal](https://github.com/rizalrizal/SimpleApiPHP)
