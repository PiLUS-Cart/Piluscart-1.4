<?php if (!defined('PILUS_SHOP')) die("Direct Access Not Allowed!");
/**
 * File utilities.php
 * Collection of library
 * 
 * @package   PiLUS_CMS
 * @author    Maoelana Noermoehammad
 * @copyright 2014 kartatopia.com
 * @license   GPL version 3.0
 * @version   1.4.0
 * @since     Since Release 1.4
 */

function loadContent($content) 
{
	
  switch($content) {
   // konten pencarian produk
	case 'searchproduct' :
	   echo searchProduct();
	   break;
   // konten pencarian article
	case 'searcharticle' :
	   echo searchArticle();
	   break;
	// Konten detail produk
	case 'productdetail' :
	   echo productDetail(); 
	   break;
	// Konten produk per kategori
	case 'prodcatdetail' :
	   echo prodcatDetail(); 
	   break;
	// Konten form kontak
	case 'contactform' :
       echo contactForm();
	   break;
	// konten keranjang belanja
	case 'basket' :
	   echo basket();
	   break;
	// konten selesai belanja
    case 'checkout' :
	   echo checkOut();		
	   break;
	// konten simpan transaksi kustomer lama
	case 'membertransaction' :		
	   echo memberTransaction();
	   break;	
	// konten simpan transaksi kustomer baru
	case 'savetransaction' :
	   echo saveTransaction();
	   break;
    // konten testimoni
	case 'testimoni':		
	   echo testimoni();
	   break;
	// konten detail static page
	case 'detailpage' :		
	  echo detailPage();
	   break;	
	// konten tampilkan semua tulisan
	case 'blog' : 
	   echo blog(); 
	   break;
	// konten detail kategori
	case 'article':		
	   echo article(); 	
	   break;
	// konten detail tulisan
	case 'blogdetail' :
	   echo blogDetail(); 	
	   break;
	// konten form registrasi member		
	case 'daftarmember' :		
		if (!$loggedInMember = Customer::isMemberLoggedIn()) {
			echo daftarMember();
		} else {
			directPage();
		}
	   break;
	// konten form login member
	case 'memberlogin' :
	  if (!$loggedInMember = Customer::isMemberLoggedIn()) {
			echo memberLogin();
	  } else {
		directPage();
	  }
	   break;
	// log out member
	case 'memberlogout' :		
	  if ($loggedInMember = Customer::isMemberLoggedIn()) {
			echo memberLogout();
	  } else {
		 directPage();
	  }
	  break;
	// konten form lupa katasandi
	case 'forgetpassword' :		
	  if (!$loggedInMember = Customer::isMemberLoggedIn()) {
			echo forgetPassword();
	  } else {
		directPage();
	  }
	  break;
	// recover kata sandi		
	case 'recoverpassword' :		
		echo recoverPassword();
	    break;
	// edit profil member	
	case 'editprofile' :
	  if ($loggedInMember = Customer::isMemberLoggedIn()) {    
		  echo editProfile();
	  } else {
		  directPage();
	  }
	  break;
	// ganti password member		
	case 'changepass':		
	   if ($loggedInMember = Customer::isMemberLoggedIn()) {
		  echo changePass();
	   } else {
		 directPage();
	   }
	   break;
	// Riwayat belanja member
	case 'shophistory' :		
	   if ($loggedInMember = Customer::isMemberLoggedIn()) {
		    echo shopHistory();
		} else {
		    directPage();
		}
	   break;
	// kirim testimoni khusus member	
	case 'sendtestimony' :
		if ($loggedInMember = Customer::isMemberLoggedIn()) {
			echo sendTestimony();
		} else {
			directPage();
		}
		break;
	// Halaman utama - front store
	default :
		echo homePage();
		break;
	}
	
	return $content;
	
}

// breadcrumb
function breadCrumb($content = NULL) 
{
	// check that browser supports $_SERVER variables
	if (isset( $_SERVER ['HTTP_REFERER'] ) && isset( $_SERVER ['HTTP_HOST'] )) {
		$url = parse_url( $_SERVER ['HTTP_REFERER'] );
		// find if visitorr was refered from a different domain
		if ($url['host'] == $_SERVER ['HTTP_HOST']) {
			// if same domain user referring URL
			return $_SERVER ['HTTP_REFERER'];
		}
	} else {
		return $content;
	}
}

// cek magic quotes - if magic quotes is turn on then use this function
function checkMagicQuotes() 
{
	if (get_magic_quotes_gpc()) {
		$process = array (
				&$_GET,
				&$_POST,
				&$_COOKIE,
				&$_REQUEST 
		);
		while ( list( $key, $val ) = each( $process ) ) {
			foreach ( $val as $k => $v ) {
				unset ( $process [$key] [$k] );
				if (is_array( $v )) {
					$process[$key][stripslashes( $k )] = $v;
					$process[] = &$process [$key] [stripslashes( $k )];
				} else {
					$process[$key][stripslashes( $k )] = stripslashes( $v );
				}
			}
		}
		unset($process);
	}
}

// fungsi pemberitahuan ke email pemilik toko online
function pushNotification($data) 
{
	if (($data['notify_title'] == "newMessage")) {
		
		// insert into pl_notification for new Message
		$push_notification = new Notification( $data );
		$push_notification->generalNotification();
		
	} elseif(($data['notify_title'] == "newOrder")) {
		// insert into pl_notification for new Order
		$push_notification = new Notification( $data );
		$push_notification->orderNotification();
	} elseif (($data['notify_title'] == "newMember")) {
		// insert into pl_notification for new Member
		$push_notification = new Notification( $data );
		$push_notification -> regMember_Notification();
	} elseif (($data['notify_title'] == "newTestimony")) {
		// insert into pl_notification for new Testimony
		$push_notification = new Notification( $data );
		$push_notification -> testimonyNotification();
	} elseif (($data['notify_title'] == "newComment")) {
		// insert into pl_notification for new comment
		$push_notification = new Notification($data);
		$push_notification -> commentNotification();
	}
	
}

// create user_activation_key
function createActivationKey($value) 
{
$activation_key = md5( mt_rand( 10000, 99999 ) . time() . $value );
return $activation_key;	
}

// generate session key
function generateSessionKey($value) 
{
	
	// create value
	$salt = 'c#haRl891';
	$value = sha1(mt_rand(1000, 9999) . time(). $salt);
	
	return $value;
}

// hash password for customer
function shieldPass($password, $id) 
{
	
	$salt = '!hi#HUde9';
	
	if (checkMagicQuotes()) {
		
		$password = stripslashes( strip_tags( htmlspecialchars( $password, ENT_QUOTES ) ) );
		
		$shield = hash_hmac( 'sha512', trim($password).$salt.$id, PL_SITEKEY );
		
		return $shield;
		
	} else {
		
		$shield = hash_hmac( 'sha512', trim($password).$salt.$id, PL_SITEKEY );
		
		return $shield;
	}
}

// fungsi batas waktu
function timeKeeper() 
{
	$time_limit = 1440;
	$_SESSION ['timeOut'] = time() + $time_limit;
}

// fungsi validasi batas waktu
function validateTimeLogIn() 
{
	$timeOut = $_SESSION['timeOut'];
	
	if (time() < $timeOut) {
		timeKeeper();
		return true;
	} else {
		
		unset( $_SESSION['timeOut'] );
		return false;
	}
}

// Autolink function
function auto_link($text) 
{
	$pattern = '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
	$text = preg_replace( $pattern, ' <a href="$1">$1</a>', $text );
	// fix URLs without protocols
	$text = preg_replace( '/href="www/', 'href="http://www', $text );
	return $text;
}

// displaying paragraph
function createParagraph($text) 
{
	$text = trim( $text );
	
	return '<p>' .preg_replace( '/[\r\n]+/', '</p><p>', $text ). '</p>';
}

// prevent from injection
function preventInject($data) 
{
	
	$data = @trim( stripslashes( strip_tags( htmlspecialchars( $data, ENT_QUOTES ) ) ) );
	
	return $data;
}

// mengganti spasi menjadi garis bawah pada nama file
function renameFileImage($filename)
{
	return preg_replace('/\s+/', '_', $filename);
}

// fungsi redirect page
function directPage($page = 'index.php') 
{
	
	// defining url
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	// remove any trailing slashes
	$url = rtrim($url, '/\\');
	
	// add the page
	$url .= '/' . $page;
	
	// redirect the user
	header("Location: $url");
	
	exit(); // quit 
	
}

// fungsi tgl_Lokal berfungsi untuk memformat tanggal ke penanggalan lokal
function tgl_Lokal($tgl) 
{
	$tanggal = substr( $tgl, 8, 2 );
	$bulan = getBulan( substr( $tgl, 5, 2 ) );
	$tahun = substr( $tgl, 0, 4 );
	return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

/**
 * fungsi getBulan
 * fungsi untuk mendapatkan
 * nama bulan dalam bahasa indonesia
 *
 * @param string $bln        	
 */
function getBulan($bln) 
{
	switch ($bln) {
		case 1 :
			return "Januari";
			break;
		case 2 :
			return "Februari";
			break;
		case 3 :
			return "Maret";
			break;
		case 4 :
			return "April";
			break;
		case 5 :
			return "Mei";
			break;
		case 6 :
			return "Juni";
			break;
		case 7 :
			return "Juli";
			break;
		case 8 :
			return "Agustus";
			break;
		case 9 :
			return "September";
			break;
		case 10 :
			return "Oktober";
			break;
		case 11 :
			return "November";
			break;
		case 12 :
			return "Desember";
			break;
	}
}

// sanitize header email by Kevin Waterson
function safeEmail($string) 
{
	return preg_replace ( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i', '', $string );
}

// clean sanitize database content on output
function cleanOutput($text) 
{
	return stripslashes(html_entity_decode(nl2br($text)));
}

// fungsi untuk cek form pengisian
function filled_out($form_vars) 
{
	foreach ($form_vars as $key => $value) {
		if (!isset ( $key ) || $value == '') {
			
			return false;
		}
	}
	
	return true;
}

// fungsi cek valid alamat email
function valid_email($address) 
{
	
	// cek valid alamat email
	if (filter_var(trim($address), FILTER_VALIDATE_EMAIL)) {
	
		return true;
	
	} else {
	
		return false;
	}
}


#
# RFC 822/2822/5322 Email Parser
#
# By Cal Henderson <cal@iamcal.com>
#
# This code is dual licensed:
# CC Attribution-ShareAlike 2.5 - http://creativecommons.org/licenses/by-sa/2.5/
# GPLv3 - http://www.gnu.org/copyleft/gpl.html
#
# $Revision$
#

##################################################################################

function is_valid_email_address($email, $options=array()){

	#
	# you can pass a few different named options as a second argument,
	# but the defaults are usually a good choice.
	#

	$defaults = array(
			'allow_comments'	=> true,
			'public_internet'	=> true, # turn this off for 'strict' mode
	);

	$opts = array();
	foreach ($defaults as $k => $v) $opts[$k] = isset($options[$k]) ? $options[$k] : $v;
	$options = $opts;



	####################################################################################
	#
	# NO-WS-CTL       =       %d1-8 /         ; US-ASCII control characters
	#                         %d11 /          ;  that do not include the
	#                         %d12 /          ;  carriage return, line feed,
	#                         %d14-31 /       ;  and white space characters
	#                         %d127
	# ALPHA          =  %x41-5A / %x61-7A   ; A-Z / a-z
	# DIGIT          =  %x30-39

	$no_ws_ctl	= "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
	$alpha		= "[\\x41-\\x5a\\x61-\\x7a]";
	$digit		= "[\\x30-\\x39]";
	$cr		= "\\x0d";
	$lf		= "\\x0a";
	$crlf		= "(?:$cr$lf)";


	####################################################################################
	#
	# obs-char        =       %d0-9 / %d11 /          ; %d0-127 except CR and
	#                         %d12 / %d14-127         ;  LF
	# obs-text        =       *LF *CR *(obs-char *LF *CR)
	# text            =       %d1-9 /         ; Characters excluding CR and LF
	#                         %d11 /
	#                         %d12 /
	#                         %d14-127 /
	#                         obs-text
	# obs-qp          =       "\" (%d0-127)
	# quoted-pair     =       ("\" text) / obs-qp

	$obs_char	= "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
	$obs_text	= "(?:$lf*$cr*(?:$obs_char$lf*$cr*)*)";
	$text		= "(?:[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";

	#
	# there's an issue with the definition of 'text', since 'obs_text' can
	# be blank and that allows qp's with no character after the slash. we're
	# treating that as bad, so this just checks we have at least one
	# (non-CRLF) character
	#

	$text		= "(?:$lf*$cr*$obs_char$lf*$cr*)";
	$obs_qp		= "(?:\\x5c[\\x00-\\x7f])";
	$quoted_pair	= "(?:\\x5c$text|$obs_qp)";


	####################################################################################
	#
	# obs-FWS         =       1*WSP *(CRLF 1*WSP)
	# FWS             =       ([*WSP CRLF] 1*WSP) /   ; Folding white space
	#                         obs-FWS
	# ctext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33-39 /       ; The rest of the US-ASCII
	#                         %d42-91 /       ;  characters not including "(",
	#                         %d93-126        ;  ")", or "\"
	# ccontent        =       ctext / quoted-pair / comment
	# comment         =       "(" *([FWS] ccontent) [FWS] ")"
	# CFWS            =       *([FWS] comment) (([FWS] comment) / FWS)

	#
	# note: we translate ccontent only partially to avoid an infinite loop
	# instead, we'll recursively strip *nested* comments before processing
	# the input. that will leave 'plain old comments' to be matched during
	# the main parse.
	#

	$wsp		= "[\\x20\\x09]";
	$obs_fws	= "(?:$wsp+(?:$crlf$wsp+)*)";
	$fws		= "(?:(?:(?:$wsp*$crlf)?$wsp+)|$obs_fws)";
	$ctext		= "(?:$no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
	$ccontent	= "(?:$ctext|$quoted_pair)";
	$comment	= "(?:\\x28(?:$fws?$ccontent)*$fws?\\x29)";
	$cfws		= "(?:(?:$fws?$comment)*(?:$fws?$comment|$fws))";


	#
	# these are the rules for removing *nested* comments. we'll just detect
	# outer comment and replace it with an empty comment, and recurse until
	# we stop.
	#

	$outer_ccontent_dull	= "(?:$fws?$ctext|$quoted_pair)";
	$outer_ccontent_nest	= "(?:$fws?$comment)";
	$outer_comment		= "(?:\\x28$outer_ccontent_dull*(?:$outer_ccontent_nest$outer_ccontent_dull*)+$fws?\\x29)";


	####################################################################################
	#
	# atext           =       ALPHA / DIGIT / ; Any character except controls,
	#                         "!" / "#" /     ;  SP, and specials.
	#                         "$" / "%" /     ;  Used for atoms
	#                         "&" / "'" /
	#                         "*" / "+" /
	#                         "-" / "/" /
	#                         "=" / "?" /
	#                         "^" / "_" /
	#                         "`" / "{" /
	#                         "|" / "}" /
	#                         "~"
	# atom            =       [CFWS] 1*atext [CFWS]

	$atext		= "(?:$alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
	$atom		= "(?:$cfws?(?:$atext)+$cfws?)";


	####################################################################################
	#
	# qtext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33 /          ; The rest of the US-ASCII
	#                         %d35-91 /       ;  characters not including "\"
	#                         %d93-126        ;  or the quote character
	# qcontent        =       qtext / quoted-pair
	# quoted-string   =       [CFWS]
	#                         DQUOTE *([FWS] qcontent) [FWS] DQUOTE
	#                         [CFWS]
	# word            =       atom / quoted-string

	$qtext		= "(?:$no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
	$qcontent	= "(?:$qtext|$quoted_pair)";
	$quoted_string	= "(?:$cfws?\\x22(?:$fws?$qcontent)*$fws?\\x22$cfws?)";

	#
	# changed the '*' to a '+' to require that quoted strings are not empty
	#

	$quoted_string	= "(?:$cfws?\\x22(?:$fws?$qcontent)+$fws?\\x22$cfws?)";
	$word		= "(?:$atom|$quoted_string)";


	####################################################################################
	#
	# obs-local-part  =       word *("." word)
	# obs-domain      =       atom *("." atom)

	$obs_local_part	= "(?:$word(?:\\x2e$word)*)";
	$obs_domain	= "(?:$atom(?:\\x2e$atom)*)";


	####################################################################################
	#
	# dot-atom-text   =       1*atext *("." 1*atext)
	# dot-atom        =       [CFWS] dot-atom-text [CFWS]

	$dot_atom_text	= "(?:$atext+(?:\\x2e$atext+)*)";
	$dot_atom	= "(?:$cfws?$dot_atom_text$cfws?)";


	####################################################################################
	#
	# domain-literal  =       [CFWS] "[" *([FWS] dcontent) [FWS] "]" [CFWS]
	# dcontent        =       dtext / quoted-pair
	# dtext           =       NO-WS-CTL /     ; Non white space controls
	#
	#                         %d33-90 /       ; The rest of the US-ASCII
	#                         %d94-126        ;  characters not including "[",
	#                                         ;  "]", or "\"

	$dtext		= "(?:$no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
	$dcontent	= "(?:$dtext|$quoted_pair)";
	$domain_literal	= "(?:$cfws?\\x5b(?:$fws?$dcontent)*$fws?\\x5d$cfws?)";


	####################################################################################
	#
	# local-part      =       dot-atom / quoted-string / obs-local-part
	# domain          =       dot-atom / domain-literal / obs-domain
	# addr-spec       =       local-part "@" domain

	$local_part	= "(($dot_atom)|($quoted_string)|($obs_local_part))";
	$domain		= "(($dot_atom)|($domain_literal)|($obs_domain))";
	$addr_spec	= "$local_part\\x40$domain";



	#
	# this was previously 256 based on RFC3696, but dominic's errata was accepted.
	#

	if (strlen($email) > 254) return 0;


	#
	# we need to strip nested comments first - we replace them with a simple comment
	#

	if ($options['allow_comments']){

		$email = email_strip_comments($outer_comment, $email, "(x)");
	}


	#
	# now match what's left
	#

	if (!preg_match("!^$addr_spec$!", $email, $m)){

		return 0;
	}

	$bits = array(
			'local'			=> isset($m[1]) ? $m[1] : '',
			'local-atom'		=> isset($m[2]) ? $m[2] : '',
			'local-quoted'		=> isset($m[3]) ? $m[3] : '',
			'local-obs'		=> isset($m[4]) ? $m[4] : '',
			'domain'		=> isset($m[5]) ? $m[5] : '',
			'domain-atom'		=> isset($m[6]) ? $m[6] : '',
			'domain-literal'	=> isset($m[7]) ? $m[7] : '',
			'domain-obs'		=> isset($m[8]) ? $m[8] : '',
	);


	#
	# we need to now strip comments from $bits[local] and $bits[domain],
	# since we know they're in the right place and we want them out of the
	# way for checking IPs, label sizes, etc
	#

	if ($options['allow_comments']){
		$bits['local']	= email_strip_comments($comment, $bits['local']);
		$bits['domain']	= email_strip_comments($comment, $bits['domain']);
	}


	#
	# length limits on segments
	#

	if (strlen($bits['local']) > 64) return 0;
	if (strlen($bits['domain']) > 255) return 0;


	#
	# restrictions on domain-literals from RFC2821 section 4.1.3
	#
	# RFC4291 changed the meaning of :: in IPv6 addresses - i can mean one or
	# more zero groups (updated from 2 or more).
	#

	if (strlen($bits['domain-literal'])){

		$Snum			= "(\d{1,3})";
		$IPv4_address_literal	= "$Snum\.$Snum\.$Snum\.$Snum";

		$IPv6_hex		= "(?:[0-9a-fA-F]{1,4})";

		$IPv6_full		= "IPv6\:$IPv6_hex(?:\:$IPv6_hex){7}";

		$IPv6_comp_part		= "(?:$IPv6_hex(?:\:$IPv6_hex){0,7})?";
		$IPv6_comp		= "IPv6\:($IPv6_comp_part\:\:$IPv6_comp_part)";

		$IPv6v4_full		= "IPv6\:$IPv6_hex(?:\:$IPv6_hex){5}\:$IPv4_address_literal";

		$IPv6v4_comp_part	= "$IPv6_hex(?:\:$IPv6_hex){0,5}";
		$IPv6v4_comp		= "IPv6\:((?:$IPv6v4_comp_part)?\:\:(?:$IPv6v4_comp_part\:)?)$IPv4_address_literal";


		#
		# IPv4 is simple
		#

		if (preg_match("!^\[$IPv4_address_literal\]$!", $bits['domain'], $m)){

			if (intval($m[1]) > 255) return 0;
			if (intval($m[2]) > 255) return 0;
			if (intval($m[3]) > 255) return 0;
			if (intval($m[4]) > 255) return 0;

		}else{

			#
			# this should be IPv6 - a bunch of tests are needed here :)
			#

			while (1){

				if (preg_match("!^\[$IPv6_full\]$!", $bits['domain'])){
					break;
				}

				if (preg_match("!^\[$IPv6_comp\]$!", $bits['domain'], $m)){
					list($a, $b) = explode('::', $m[1]);
					$folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
					$groups = explode(':', $folded);
					if (count($groups) > 7) return 0;
					break;
				}

				if (preg_match("!^\[$IPv6v4_full\]$!", $bits['domain'], $m)){

					if (intval($m[1]) > 255) return 0;
					if (intval($m[2]) > 255) return 0;
					if (intval($m[3]) > 255) return 0;
					if (intval($m[4]) > 255) return 0;
					break;
				}

				if (preg_match("!^\[$IPv6v4_comp\]$!", $bits['domain'], $m)){
					list($a, $b) = explode('::', $m[1]);
					$b = substr($b, 0, -1); # remove the trailing colon before the IPv4 address
					$folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
					$groups = explode(':', $folded);
					if (count($groups) > 5) return 0;
					break;
				}

				return 0;
			}
		}
	}else{

		#
		# the domain is either dot-atom or obs-domain - either way, it's
		# made up of simple labels and we split on dots
		#

		$labels = explode('.', $bits['domain']);


		#
		# this is allowed by both dot-atom and obs-domain, but is un-routeable on the
		# public internet, so we'll fail it (e.g. user@localhost)
		#

		if ($options['public_internet']){
			if (count($labels) == 1) return 0;
		}


		#
		# checks on each label
		#

		foreach ($labels as $label){

			if (strlen($label) > 63) return 0;
			if (substr($label, 0, 1) == '-') return 0;
			if (substr($label, -1) == '-') return 0;
		}


		#
		# last label can't be all numeric
		#

		if ($options['public_internet']){
			if (preg_match('!^[0-9]+$!', array_pop($labels))) return 0;
		}
	}


	return 1;
}

##################################################################################

function email_strip_comments($comment, $email, $replace=''){

	while (1){
		$new = preg_replace("!$comment!", $replace, $email);
		if (strlen($new) == strlen($email)){
			return $email;
		}
		$email = $new;
	}
}

##################################################################################

// fungsi validasi alamat website
function validHttp($url) 
{
	if (!preg_match( "@^[hf]tt?ps?://@", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}

// Upload gambar kategori produk
function uploadProdcat($file_name) 
{
	
	$path = "../content/uploads/products/";
	$path_thumb = "../content/uploads/products/thumbs/";
	
	// save original pic size
	$file_resource = $path . $file_name;
	
	move_uploaded_file($_FILES['image']['tmp_name'], $file_resource);
	
	// identitas file asli
	$imageResource = imagecreatefromjpeg($file_resource);
	$src_width = imageSX($imageResource);
	$src_height = imageSY($imageResource);
	
	// Set ukuran gambar hasil perubahan
	$dst_width = 120;
	$dst_height = ($dst_width / $src_width) * $src_height;
	
	// proses perubahan ukuran
	$imageThumb = imagecreatetruecolor($dst_width, $dst_height);
	imagecopyresampled($imageThumb, $imageResource, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
	
	// Simpan perubahan gambar
	imagejpeg($imageThumb, $path_thumb . "thumb_" . $file_name);
	
	// Hapus gambar di memori komputer
	imagedestroy($imageResource);
	imagedestroy($imageThumb);
	
}

// fungsi upload gambar produk
function uploadProductImage($file_name) 
{
	
	$path = "../content/uploads/products/";
	$path_thumb = "../content/uploads/products/thumbs/";
	
	// save original resource
	$file_resource = $path . $file_name;
	
	move_uploaded_file($_FILES['image']['tmp_name'], $file_resource);
	
	// identitas file asli
	$imageResource = imagecreatefromjpeg( $file_resource );
	$src_width = imageSX( $imageResource );
	$src_height = imageSY( $imageResource );
	
	// Set ukuran gambar hasil perubahan
	$dst_width = 140;
	$dst_height = ($dst_width / $src_width) * $src_height;
	
	// proses perubahan ukuran
	$imageThumb = imagecreatetruecolor( $dst_width, $dst_height );
	imagecopyresampled( $imageThumb, $imageResource, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height );
	
	// Simpan perubahan gambar
	imagejpeg( $imageThumb, $path_thumb . "thumb" . $file_name );
	
	// Hapus gambar di memori komputer
	imagedestroy( $imageResource );
	imagedestroy( $imageThumb );
	
}

// fungsi upload gambar banner
function uploadBanner($file_name) 
{
	$path = "../content/uploads/images/";
	$path_thumb = "../content/uploads/images/thumbs/";
	
	// save original pic size
	$file_resource = $path . $file_name;
	
	move_uploaded_file($_FILES['image']['tmp_name'], $file_resource);
	
	// identitas file asli
	$imageResource = imagecreatefromjpeg( $file_resource );
	$src_width = imageSX( $imageResource );
	$src_height = imageSY( $imageResource );
	
	// Set ukuran gambar hasil perubahan
	$dst_width = 110;
	$dst_height = ($dst_width / $src_width) * $src_height;
	
	// proses perubahan ukuran
	$imageThumb = imagecreatetruecolor( $dst_width, $dst_height );
	imagecopyresampled( $imageThumb, $imageResource, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height );
	
	// Simpan perubahan gambar
	imagejpeg( $imageThumb, $path_thumb . "thumb_" . $file_name );
	
	// Hapus gambar di memori komputer
	imagedestroy( $imageResource );
	imagedestroy( $imageThumb );
	
}

// fungsi upload file
function uploadFile() 
{
	$path = "../content/uploads/files/";
	
	$uploader = new Uploader ( 'fdoc' );
	$uploader->saveIn( $path );
	$fileUploaded = $uploader->save();
}

// fungsi upload favicon
function uploadFavicon($file_name) 
{
	
	$path = "../content/uploads/images/";
	$path_thumb = "../content/uploads/images/thumbs/";
	
	// save original pic size
	$file_resource = $path . $file_name;
	
	$uploader = new Uploader( 'image' );
	$uploader->saveIn( $path );
	$faviconUpload = $uploader->save();
	
	// identitas file asli
	$imageResource = imagecreatefrompng( $file_resource );
	$src_width = imageSX( $imageResource );
	$src_height = imageSY( $imageResource );
	
	// Set ukuran gambar hasil perubahan
	$dst_width = 15;
	$dst_height = ($dst_width / $src_width) * $src_height;
	
	// proses perubahan ukuran
	$imageThumb = imagecreatetruecolor($dst_width, $dst_height);
	imagecopyresampled($imageThumb, $imageResource, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
	
	// Simpan perubahan gambar
	imagepng($imageThumb, $path_thumb . "thumb_" . $file_name);
	
	// Hapus gambar di memori komputer
	imagedestroy($imageResource);
	imagedestroy($imageThumb);
	
}

// upload avatar
function uploadAvatar($file_name) 
{
	
	$path = "../content/uploads/images/";
	$path_thumb = "../content/uploads/images/thumbs/";
	
	// save original resource
	$file_resource = $path . $file_name;
	
	$uploader = new Uploader( 'image' );
	$uploader->saveIn( $path );
	$fileUploaded = $uploader->save();
	
	// identitas file asli
	$imageResource = imagecreatefromjpeg( $file_resource );
	$src_width = imageSX( $imageResource );
	$src_height = imageSY( $imageResource );
	
	// Set ukuran gambar hasil perubahan
	$dst_width = 120;
	$dst_height = ($dst_width / $src_width) * $src_height;
	
	// proses perubahan ukuran
	$imageThumb = imagecreatetruecolor( $dst_width, $dst_height );
	imagecopyresampled( $imageThumb, $imageResource, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height );
	
	// Simpan perubahan gambar
	imagejpeg($imageThumb, $path_thumb . "thumb_" . $file_name);
	
	// Hapus gambar di memori komputer
	imagedestroy($imageResource);
	imagedestroy($imageThumb);
	
}

// fungsi install template dengan mengunggah file template berekstensi .zip
function uploadTheme($file_name) 
{
	
	$path = "../content/themes/";
	
	$file_open = $path . $file_name;
	
	$uploader = new Uploader( 'zip_file' );
	$uploader->saveIn( $path );
	$fileUploaded = $uploader->save();
	
	$archive = new PclZip( $file_open );
	$archive->extract(PCLZIP_OPT_PATH, $path);
	
	unlink( $file_open );
	
}

// fungsi install modul dengan mengunggah file modul berekstensi .zip
function uploadModul($file_name, $file_location) 
{
	
	$pecah = explode( ".", $file_name );
	$ekstensi = $pecah[1];
	$title = $pecah[0];
	$slug = makeSlug( $title );
	$acak = rand( 000000, 999999 );
	$nama_file = "-pilus.";
	$nama_file_unik = $slug . '-' . $acak . $nama_file . $ekstensi;
	$namaDir = '../studio/module/';
	$pathFile = $namaDir . $nama_file_unik;
	
	move_uploaded_file($file_location, $pathFile);
	
	$archive = new PclZip( $pathFile );
	$archive->extract(PCLZIP_OPT_PATH, $namaDir);
	unlink("../studio/module/$nama_file_unik");
}

// funsi seo friendly URL
function makeSlug($slug) 
{
	
	// replace non letter or digits by -
	$slug = preg_replace( '~[^\\pL\d]+~u', '-', $slug);
	
	// trim
	$slug = trim($slug, '-');
	
	// transliterate
	$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
	
	// lowercase
	$slug = strtolower($slug);
	
	// remove unwanted characters
	$slug = preg_replace('~[^-\w]+~', '', $slug);
	
	if (empty($slug)) {
		return 'n-a';
	}
	
	return $slug;
}

// fungsi format mata uang Rupiah
function idrFormat($number) 
{
	$rupiah=number_format($number,0,',','.');
	return $rupiah;
}

// Konversi format tanggal dd-mm-yyyy -> yyyy-mm-dd
function tgl_ind_to_eng($tgl) 
{
	$tgl_eng = substr($tgl, 6, 4) . "-" . substr($tgl, 3, 2) . "-" . substr($tgl, 0, 2);
	return $tgl_eng;
}

// Konversi format tanggal yyyy-mm-dd -> dd-mm-yyyy
function tgl_eng_to_ind($tgl) 
{
	$tgl_ind = substr($tgl, 8, 2) . "-" . substr($tgl, 5, 2) . "-" . substr( $tgl, 0, 4);
	return $tgl_ind;
}

// fungsi konversi rupiah
function rupiah($money) 
{
	
	$Rp = "";
	$digit = strlen( $money );
	
	while ($digit > 3) {
		$Rp = "." . substr( $money, - 3) . $Rp;
		$lebar = strlen( $money ) - 3;
		$money = substr( $money, 0, $lebar );
		$digit = strlen( $money );
	}
	
	$Rp = $money . $Rp . ",-";
	
	return $Rp;
}

// fungsi combo tanggal
function comboBox_Tanggal($awal, $akhir, $var, $terpilih) 
{
	echo "<select name=$var>";
	for($i = $awal; $i <= $akhir; $i ++) {
		$lebar = strlen( $i );
		switch ($lebar) {
			case 1 :
				{
					$g = "0" . $i;
					break;
				}
			case 2 :
				{
					$g = $i;
					break;
				}
		}
		if ($i == $terpilih)
			echo "<option value=$g selected>$g</option>";
		else
			echo "<option value=$g>$g</option>";
	}
	echo "</select> ";
}

// fungsi combo box Bulan
function comboBox_Bulan($awal, $akhir, $var, $terpilih) 
{
	echo "<select name=$var>";
	for($bln = $awal; $bln <= $akhir; $bln ++) {
		$lebar = strlen( $bln );
		switch ($lebar) {
			case 1 :
				{
					$b = "0" . $bln;
					break;
				}
			case 2 :
				{
					$b = $bln;
					break;
				}
		}
		if ($bln == $terpilih)
			echo "<option value=$b selected>$b</option>";
		else
			echo "<option value=$b>$b</option>";
	}
	echo "</select> ";
}

// fungsi Combo box Tahun
function comboBox_Tahun($awal, $akhir, $var, $terpilih) 
{
	echo "<select name=$var>";
	for($i = $awal; $i <= $akhir; $i ++) {
		if ($i == $terpilih)
			echo "<option value=$i selected>$i</option>";
		else
			echo "<option value=$i>$i</option>";
	}
	echo "</select> ";
}

// fungsi Combo Box Nama Bulan
function comboBox_NamaBulan($awal, $akhir, $var, $terpilih) 
{
	$nama_bln = array (
			1 => "Januari",
			"Februari",
			"Maret",
			"April",
			"Mei",
			"Juni",
			"Juli",
			"Agustus",
			"September",
			"Oktober",
			"November",
			"Desember" 
	);
	echo "<select name=$var>";
	for($bln = $awal; $bln <= $akhir; $bln ++) {
		if ($bln == $terpilih)
			echo "<option value=$bln selected>$nama_bln[$bln]</option>";
		else
			echo "<option value=$bln>$nama_bln[$bln]</option>";
	}
	echo "</select> ";
}

// fungsi hapus direktori
function deleteDir($dirname) 
{
	if (is_dir( $dirname ))
		$dir_handle = opendir( $dirname );
	if (!$dir_handle)
		return false;
	while ( $file = readdir( $dir_handle ) ) {
		if ($file != "." && $file != "..") {
			if (!is_dir( $dirname . "/" . $file ))
				unlink( $dirname . "/" . $file );
			else
				deleteDir( $dirname . '/' . $file );
		}
	}
	closedir( $dir_handle );
	rmdir( $dirname );
	return true;
}

// fungsi set statistik pengunjung
function statCollector() 
{
	
	global $stats;
	
	// Statistik user
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? addslashes( $_SERVER['REMOTE_ADDR'] ) : ""; // get IP address
	$browser = isset( $_SERVER['HTTP_USER_AGENT'] ) ? addslashes( $_SERVER['HTTP_USER_AGENT'] ) : "";
	
	$timezone_offset = 0;
	
	// waktu dalam detik
	$time_adjust = ($timezone_offset * 60 * 60);
	
	// kalkulasi tanggal lokal
	$local_date = date ( "Y", time() + $time_adjust ) . "-" . date( "m", time () + $time_adjust ) . "-" . date ( "d", time () + $time_adjust );
	
	$online = time(); 
	$tanggal = addslashes( $local_date );
	$waktu = date( "H:i:s" );
	
	$statCollector = $stats->createCounter ( $ip, $browser, $tanggal, $waktu, $online);
	
}

// fungsi timeago - thanks to Bennet Stone devtips.com
function timeAgo($date) 
{
	
	if (empty($date))
	{
		return "No date provided";
	}
	
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "10");
	
	$now = time();
	
	$unix_date = strtotime( $date );
	
	
	if ( empty( $unix_date ) )
	{
		return "Bad date";
	}
	
	// is it future date or past date
	
	if ( $now > $unix_date )
	{
		$difference = $now - $unix_date;
		$tense = "ago";
	}
	else 
	{
		$difference = $unix_date - $now;
		$tense = "from now";
	}
	
	for ( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
	{
		$difference /= $lengths[$j];
	}
	
	$difference = round( $difference );
	
	if ( $difference != 1 )
	{
		$periods[$j].= "s";
	}
	
	return "$difference $periods[$j] {$tense}";
	
}

// fungsi konversi kg ke gram
function weightConverter($weight) 
{
	$weight = number_format((float)$weight * 1000);
	
	return $weight;
}

// fungsi random generator
function random_generator($digits) 
{
	srand((double) microtime() * 10000000);
	//Array of alphabets
	$input = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q",
			"R", "S", "T", "U", "V", "W", "X", "Y", "Z");

	$randomGenerator = ""; // Initialize the string to store random numbers
	for ($i = 1; $i < $digits + 1; $i++) { // Loop the number of times of required digits
		if (rand(1, 2) == 1) {// to decide the digit should be numeric or alphabet
			// Add one random alphabet
			$rand_index = array_rand($input);
			$randomGenerator .=$input[$rand_index]; // One char is added
		} else {

			// Add one numeric digit between 1 and 10
			$randomGenerator .=rand(1, 10); // one number is added
		} // end of if else
	} // end of for loop

	return $randomGenerator;
}

// fungsi check module
function checkedModule($module)
{
	$moduleChecked = new Module();
	
	$getModuleActivated = $moduleChecked -> isModuleActived($module);
	
	if ($getModuleActivated -> actived == 'Y') {
		
		return true;
		
	} else {
		
		return false;
		
	}
	
}

// content size validation
function contentSizeValidation($form_fields)
{
	
	foreach ($form_fields as $k => $v) {
			
		if(!empty($_POST[$k]) && isset($_POST[$k]{$v + 1})) {
				
			exit("<blockquote><b> {$k} </b> is longer then allowed {$v} byte length </blockquote>");
			
		}
			
	}
		
}