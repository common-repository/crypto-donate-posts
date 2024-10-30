<?php
/**
 * Plugin Name:       Crypto Donate Posts
 * Description:       Add cryptocurrency donate button with payments method to your posts. Easy to use.
 * Version:           1.0.0
 * Author:            Crypto Donate Posts
 * Author URI:        https://cryptodonateposts.com
 * Text Domain:       cryptodonateposts
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/CryptoDonatePlugin/CryptoDonatePluginForWordpress
 */
 
/*
 * Plugin constants
 */
if(!defined('CDPCRYPTO_URL'))
	define('CDPCRYPTO_URL', plugin_dir_url( __FILE__ ));
if(!defined('CDPCRYPTO_PATH'))
	define('CDPCRYPTO_PATH', plugin_dir_path( __FILE__ ));
 
/*
 * Main class
 */
/**
 * Class CRYPTO
 *
 * This class creates the option page and add the web app script
 */
class CDPCrypto
{
 
    /**
     * Crypto constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct()
    {
		register_activation_hook( __FILE__, array( $this, 'create_plugin_database_table' ) );
		// Admin page calls:
		
		if(!function_exists('wp_get_current_user')) {
		include(ABSPATH . "wp-includes/pluggable.php"); 
		}
		
		
		add_action( 'admin_menu', array( $this, 'CDPaddAdminMenu' ) );
		add_action( 'wp_ajax_store_admin_data', array( $this, 'storeAdminData' ) );
		add_action('admin_init', array( $this, 'register_script'));
		
		
		
		add_filter('the_content', array( $this, 'CDPextra_content' ) ); 
		add_shortcode( 'bitcoin', array( $this,'CDPbitcoin_shortcode') );
		add_shortcode( 'dogecoin', array( $this,'CDPdogecoin_shortcode') );
		add_shortcode( 'ethereum', array( $this,'CDPethereum_shortcode') );
		add_shortcode( 'zcash', array( $this,'CDPzcash_shortcode') );
		
		
 
    }
	
	
	public function CDPaddAdminMenu()
	{
		add_menu_page(
		__( 'Crypto Donate', 'crypto' ),
		__( 'Crypto Donate', 'crypto' ),
		'manage_options',
		'crypto',
		array($this, 'CDPadminLayout'),
		plugin_dir_url( __FILE__ ) . 'img/icon.png'
		);
	}
	
	public function register_script() {
		wp_register_style('style', plugins_url('style.css',__FILE__ ));
		wp_enqueue_style('style');

	}
	
	


	
	public function CDPadminLayout()
	{
		//bitcoin
		if( isset($_POST['btcbutton']) ){
			
			if(!empty($_POST['btcwallet'])){
				$btcwallet = sanitize_text_field($_POST['btcwallet']);
				$btcen = 1;
				
				global $wpdb;
		$bitcoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin'
                )
            );
			
			if ( $bitcoin > 0 ){ 
		$table_name = $wpdb->prefix . 'wallets';

			$wpdb->update( 
			$table_name, 
			array( 
				'cryptocurrency' => 'bitcoin', 
				'wallet' => $btcwallet, 
				'enable' => 1, 
			) ,
			array('cryptocurrency' => 'bitcoin')
		);	
		
		
		}else{
			$table_name = $wpdb->prefix . 'wallets';

			$wpdb->insert( 
			$table_name, 
			array( 
				'cryptocurrency' => 'bitcoin', 
				'wallet' => $btcwallet, 
				'enable' => 1, 
			) 
		);	
		}
				
			}
			
	
		

		
			
		}
		//--------------------------------
		
		//dogecoin
		if( isset($_POST['dogebutton']) ){
			
			if(!empty($_POST['dogewallet'])){
				
				$dogewallet = sanitize_text_field($_POST['dogewallet']);
			
			if (isset($_POST['dogeen'])) {

			$dogeen = 1;
			} else {

			$dogeen = 0;
			}
			
			
	
		global $wpdb;
		$dogecoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin'
                )
            );

		if ( $dogecoin > 0 ){ 
		$table_name = $wpdb->prefix . 'wallets';

			$wpdb->update( 
			$table_name, 
			array( 
				'cryptocurrency' => 'dogecoin', 
				'wallet' => $dogewallet, 
				'enable' => $dogeen, 
			) ,
			array('cryptocurrency' => 'dogecoin')
		);	
		
		
		}else{
			$table_name = $wpdb->prefix . 'wallets';

			$wpdb->insert( 
			$table_name, 
			array( 
				'cryptocurrency' => 'dogecoin', 
				'wallet' => $dogewallet, 
				'enable' => $dogeen, 
			) 
		);	
		}
				
			}
			
			
			
			
		}
		//--------------------------------
		//lisk
		
		//--------------------------------
		
		//ethereum
		if( isset($_POST['ethbutton'])  ){
			
			if(!empty($_POST['ethwallet'])){
				$ethwallet = sanitize_text_field($_POST['ethwallet']);
			
			if (isset($_POST['ethen'])) {

			$ethen = 1;
			} else {

			$ethen = 0;
			}
			
			
	
		global $wpdb;
		$eth = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum'
                )
            );

		if ( $eth > 0 ){ 
		$table_name = $wpdb->prefix . 'wallets';

			$wpdb->update( 
			$table_name, 
			array( 
				'cryptocurrency' => 'ethereum', 
				'wallet' => $ethwallet, 
				'enable' => $ethen, 
			) ,
			array('cryptocurrency' => 'ethereum')
		);	
		
		
		}else{
			$table_name = $wpdb->prefix . 'wallets';

			$wpdb->insert( 
			$table_name, 
			array( 
				'cryptocurrency' => 'ethereum', 
				'wallet' => $ethwallet, 
				'enable' => $ethen, 
			) 
		);	
		}
			}
			
			
			
		}
		//--------------------------------
		//ethereum classic
		
		//--------------------------------
		
		//litecoin
		//--------------------------------
		
		//zcash
		if( isset($_POST['zecbutton']) ){
			
			if(!empty($_POST['zecwallet'])){
				$zecwallet = sanitize_text_field($_POST['zecwallet']);
			
			if (isset($_POST['zecen'])) {

			$zecen = 1;
			} else {

			$zecen = 0;
			}
			
			
	
		global $wpdb;
		$zec = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash'
                )
            );

		if ( $zec > 0 ){ 
		$table_name = $wpdb->prefix . 'wallets';

			$wpdb->update( 
			$table_name, 
			array( 
				'cryptocurrency' => 'zcash', 
				'wallet' => $zecwallet, 
				'enable' => $zecen, 
			) ,
			array('cryptocurrency' => 'zcash')
		);	
		
		
		}else{
			$table_name = $wpdb->prefix . 'wallets';

			$wpdb->insert( 
			$table_name, 
			array( 
				'cryptocurrency' => 'zcash', 
				'wallet' => $zecwallet, 
				'enable' => $zecen, 
			) 
		);	
		}
			}
			
			
			
		}
		//--------------------------------
		//0x
		
		//--------------------------------
		
		//active
		if( isset($_POST['activebutton']) ){
			
			if(!empty($_POST['activetext'])){
				$activetext = sanitize_text_field($_POST['activetext']);

			if (isset($_POST['activeposts'])) {

			$activeposts = 1;
			} else {

			$activeposts = 0;
			}
			
			
	
		global $wpdb;
		$active = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "activeposts", ''
                )
            );

		if ( $active > 0 ){ 
		$table_name = $wpdb->prefix . 'activeposts';

			$wpdb->update( 
			$table_name, 
			array( 
				'text' => $activetext, 
				'enable' => $activeposts, 
			) ,
			array('ID' => 1)
		);	
		
		
		}else{
			$table_name = $wpdb->prefix . 'activeposts';

			$wpdb->insert( 
			$table_name, 
			array(  
				'text' => $activetext, 
				'enable' => $activeposts, 
			) 
		);	
		}
			}
			
			
			
		}
		//--------------------------------
		
?>
   
<div class="plugin-holder">

	
			<h3 id="crypto-title">Crypto Donate Settings</h3>
			<span class="italic">Version 1.0.0</span>
            <p>
	        Add your wallets and enable cryptocurrency donation you want.<br>
			Buttons will add automatically under your posts.
            </p>
			<br>
			<h3>Activate under all posts:</h3>
			<form method = "post" action = "">
			<table>
			<tr>
			<td>Show data title: <input class="wallet-address" type="text" name="activetext" required placeholder="Donate this post"/></td>
			<td><input type="checkbox" name="activeposts"/></td>
			<td>
			<input class="change-button" type="submit" value="Change" name="activebutton"/>
			
			</td>
			</tr>
			</table></form>
			<div class="divider"></div>
			<div class="panel-group" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
					
								<table><tr>
								<td><img src="<?php echo plugin_dir_url( __FILE__ )?>/img/icon.png" /></td><td><span class="crypto-title"><b>Bitcoin (BTC)</b></span></td>
								</tr></table>
						
					</div>
				<div id="collapseOne" class="panel-collapse collapse">
				<div class="panel-body body-bitcoin">
		<div class="info-title info-bitcoin">Info:</div>
		<?php 
		
		global $wpdb;
		//bitcoin
		$bitcoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin'
                )
            );

		if ( $bitcoin > 0 ){ 
		$thebtc = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin' ) );
					
					
		?>
		
			<table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td><?php echo $thebtc->wallet; ?></td> <!----wyswietlenie z bazy 2---->
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>Yes (Always Enable)</td>
		</tr>
		</table>	
		<?php }else{ ?>

			<table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td>13JDhP1YScpJosCbkXwWUeS9EatNKgfVmW</td> <!----wyswietlenie tekstu---->
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>Yes (Always Enable)</td>
		</tr>
		</table>	
		<?php }	?>
        
		<div class="info-title info-bitcoin">Change:</div>
		<form method = "post" action = "">
		<table>
		<tr>
		<td style="width:180px;"><b>Change address:</b></td><td><input class="wallet-address" type="text" name="btcwallet" required/></td>
		</tr>
		
		<tr>
		<td style="width:180px;"><input class="change-button" type="submit" value="Change" name="btcbutton"/></td> <!----change 3---->
		
		</tr>
		</table>
		</form>
		<!----shortcode pojedyńczy 7---->
		<div class="info-title info-bitcoin">Bitcoin shortcode:</div>
		<div><input type="text" value="[bitcoin]" class="single-crypto-shortcode" readonly/>
		<span style="font-style: italic;">*You can use shortcode only while crypto is enabled. Only one shortcode on page.<br></span></div>
		<div class="info-title info-bitcoin">Dont have bitcoin wallet?:</div>
		<span>Create here: <a href="https://www.coinbase.com/join/5a258c3c8004c7013471f1b8">Free Bitcoin Wallet</a></span>
      </div>
    </div>
  </div>
  <div class="divider"></div>
  <div class="panel panel-default">
    <div class="panel-heading">
	<table><tr>
	<td> <img src="<?php echo plugin_dir_url( __FILE__ )?>/img/doge.png" /></td><td><span class="crypto-title"><b>Dogecoin (DOGE)</b></span></td>
	</tr></table>
     
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body body-dogecoin">
       <div class="info-title info-dogecoin">Info:</div>
	   
	   
	   <?php 
		
		global $wpdb;
		//dogecoin
		$dogecoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin'
                )
            );

		if ( $dogecoin > 0 ){ 
		$thedoge = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin' ) );
					
					
		?>
		
			<table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td><?php echo $thedoge->wallet; ?></td> <!----wyswietlenie z bazy 2---->
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>
		<?php if($thedoge->enable == 1){
			echo 'Yes';
		}else{
			echo 'No';
		}?></td>
		</tr>
		
		</table>

		<?php }else{ ?>
	   
        <table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td>DCQ13BmPHFv7s12xvHUfz6de2wqQbuTxFw</td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>No</td>
		</tr>
		</table>
		<?php } ?>
		
		 <div class="info-title info-dogecoin">Change:</div>
		<form method = "post" action = "">
		<table>
		<tr>
		<td style="width:180px;"><b>Change address:</b></td><td><input class="wallet-address" type="text" name="dogewallet" required/></td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable/Disable?:</b></td><td><input type="checkbox" name="dogeen"/></td>
		</tr>
		<tr>
		<td style="width:180px;"><input class="change-button" type="submit" value="Change" name="dogebutton"/></td>
		
		</tr>
		</table>
		</form>
		<div class="info-title info-dogecoin">Dogecoin shortcode:</div>
		<div><input type="text" value="[dogecoin]" class="single-crypto-shortcode" readonly/>
		<span style="font-style: italic;">*You can use shortcode only while crypto is enabled. Only one shortcode on page.<br></span></div>
		<div class="info-title info-dogecoin">Dont have dogecoin wallet?:</div>
		<span>Create here: <a href="https://my.dogechain.info">Free Dogecoin Wallet</a></span>
      </div>
    </div>
  </div>
  <div class="divider"></div>
  <div class="panel panel-default">
    <div class="panel-heading">
	<table><tr>
	<td><img src="<?php echo plugin_dir_url( __FILE__ )?>/img/eth.png" /></td><td><span class="crypto-title"><b>Ethereum (ETH)</b></span></td>
	</tr></table>
    
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body body-ethereum">
         <div class="info-title info-ethereum">Info:</div>
		 
		 <?php 
		
		global $wpdb;
		//ethereum
		$ethereum = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum'
                )
            );

		if ( $ethereum > 0 ){ 
		$theeth = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum' ) );
					
					
		?>
		
			<table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td><?php echo $theeth->wallet; ?></td> <!----wyswietlenie z bazy 2---->
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>
		<?php if($theeth->enable == 1){
			echo 'Yes';
		}else{
			echo 'No';
		}?></td>
		</tr>
		
		</table>

		<?php }else{ ?>
		 
        <table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td>0x828c43dae89fa5a9e3d656335830a218a6d0d9a5</td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>No</td>
		</tr>
		</table>
		<?php } ?>
		<div class="info-title info-ethereum">Change:</div>
		<form method = "post" action = "">
		<table>
		<tr>
		<td style="width:180px;"><b>Change address:</b></td><td><input class="wallet-address" type="text" name="ethwallet" required/></td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable/Disable?:</b></td><td><input type="checkbox" name="ethen"/></td>
		</tr>
		<tr>
		<td style="width:180px;"><input class="change-button" type="submit" value="Change" name="ethbutton"/></td>
		
		</tr>
		</table>
		</form>
		<div class="info-title info-ethereum">Ethereum shortcode:</div>
		<div><input type="text" value="[ethereum]" class="single-crypto-shortcode" readonly/>
		<span style="font-style: italic;">*You can use shortcode only while crypto is enabled. Only one shortcode on page.<br></span></div>
		<div class="info-title info-ethereum">Dont have ethereum wallet?:</div>
		<span>Create here: <a href="https://www.coinbase.com/join/5a258c3c8004c7013471f1b8">Free Ethereum Wallet</a></span>
      </div>
    </div>
  </div>
  
  
  <div class="divider"></div>
  <div class="panel panel-default">
    <div class="panel-heading">
	<table><tr>
	<td><img src="<?php echo plugin_dir_url( __FILE__ )?>/img/zcash.png" /></td><td><span class="crypto-title"><b>Zcash (ZEC)</b></span></td>
	</tr></table>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse">
      <div class="panel-body body-zcash">
         <div class="info-title info-zcash">Info:</div>
		 <?php 
		
		global $wpdb;
		//zcash
		$zcash = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash'
                )
            );

		if ( $zcash > 0 ){ 
		$thezec = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash' ) );
					
					
		?>
		
			<table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td><?php echo $thezec->wallet; ?></td> <!----wyswietlenie z bazy 2---->
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>
		<?php if($thezec->enable == 1){
			echo 'Yes';
		}else{
			echo 'No';
		}?></td>
		</tr>
		</table>
		<?php }else{ ?>  
        <table>
		<tr>
		<td style="width:180px;"><b>Wallet address:</b></td><td>t1Uhd689122TJXSkyE5DbPiQMFQevYEXPts</td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable?:</b></td><td>No</td>
		</tr>
		</table>
		<?php } ?>
		<div class="info-title info-zcash">Change:</div>
		<form method = "post" action = "">
		<table>
		<tr>
		<td style="width:180px;"><b>Change address:</b></td><td><input class="wallet-address" type="text" name="zecwallet" required/></td>
		</tr>
		<tr>
		<td style="width:180px;"><b>Enable/Disable?:</b></td><td><input type="checkbox" name="zecen" /></td>
		</tr>
		<tr>
		<td style="width:180px;"><input class="change-button" type="submit" value="Change" name="zecbutton"/></td>
		
		</tr>
		</table>
		</form>
		<div class="info-title info-zcash">Zcash shortcode:</div>
		<div><input type="text" value="[zcash]" class="single-crypto-shortcode" readonly/>
		<span style="font-style: italic;">*You can use shortcode only while crypto is enabled. Only one shortcode on page.<br></span></div>
		<div class="info-title info-zcash">Dont have zcash wallet?:</div>
		<span>Create here: <a href="https://www.coinbase.com/join/5a258c3c8004c7013471f1b8">Free Zcash Wallet</a></span>
      </div>
    </div>
  </div>
  <div class="divider"></div>
  
  
</div>
<!----przycisk akceptacji 4---->

<!----shortcode 6---->
	<br>
			<div class="alert alert-info" role="alert">
  <div class="info-important">Important!</div><br>This plugin is free to use!!! The default options wallet addresses they are plugin author addresses.<br>
  Our addresses:<br>
  <strong>Bitcoin:</strong> 38YcpK8qoxfrK8Dk5zT9XjM1VhuGKpCK3N<br>
  <strong>Dogecoin:</strong> DCQ13BmPHFv7s12xvHUfz6de2wqQbuTxFw<br>
  <strong>Ethereum:</strong> 0xb469e2085bAD991C6d964Ff1E7D9F6DFfFECA819<br>
  <strong>Paypal:</strong> https://paypal.me/jatazdzary<br>
  If you want to help to upgrade this project in future, you can donate us also.<br>
  All donations goes to upgrade this plugin.<br>
  You can check what will we do in future. Just visit our site: <a href="http://cryptodonateposts.com" target="_blank">http://cryptodonateposts.com</a>
</div>

<div class="info-media">Our media:</div>
<b>Site:</b> <a href="http://cryptodonateposts.com" target="_blank">http://cryptodonateposts.com</a><br>
<b>Fanpage:</b> <a href="https://www.facebook.com/CryptoDonatePluginForWordpress" target="_blank">https://www.facebook.com/CryptoDonatePluginForWordpress</a><br>
<b>Youtube:</b> <a href="https://www.youtube.com/channel/UCno0LQ-LemOXcyw59gUImMw" target="_blank">https://www.youtube.com/channel/UCno0LQ-LemOXcyw59gUImMw</a><br>
<b>Github:</b> <a href="https://github.com/CryptoDonatePlugin/CryptoDonatePluginForWordpress" target="_blank">https://github.com/CryptoDonatePlugin/CryptoDonatePluginForWordpress</a><br>


	
	</div>
 
	<?php
 
	}
	
	
	
	//bitcoin shortcode
	function CDPbitcoin_shortcode() {
	
	global $wpdb;
	$data = "";
	$btcwallet="3GPy46bhhitGL57vF8C4bUunxFrHNQmCFx";
	$bitcoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin'
                )
            );

		if ( $bitcoin > 0 ){ 
		$thebtc = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin' ) );
					
					if($thebtc->enable == 1){
						$btcwallet=$thebtc->wallet;
						$data = "<script src='".plugin_dir_url( __FILE__ )."assets/js/qrious.js'></script>
			<script language='javascript'>
			function bitshort(){
				var qr = new QRious({
				element: document.getElementById('qrbitcoinshortcode'),
				value: 'bitcoin:".$btcwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
			}
				
				window.onload = bitshort;
			</script>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-shortcode{
				background-color:white;
				width:300px;
				height:310px;
				border-radius:5px;
				border:1px solid #999;
			}
			
			
			.showme-title{
				width:100%;
				text-align:center;
				clear:both;
				font-family: 'K2D', sans-serif;
				text-transform:uppercase;
				font-size:14px;
			}
			
			.wallet-address{
				font-family: 'K2D', sans-serif;
				font-size:10px;
				border:solid 1px #999;
				background-color:#eee;
				border-radius:2px;
			}
			
			
			</style>
			<div class='donate-shortcode'>
			<div class='showme-title'>Wallet address of <span>bitcoin</span></div>
			<div class='wallet-address' style='text-align:center;font-size:10px;'>".$btcwallet."</div>
			<div class='qr-qode' style='text-align:center;'>
			<canvas id='qrbitcoinshortcode'></canvas>
			</div>
			</div>
			
			";
        
					}else{
			$data = "";
		}
					
		}
    return $data;
	}
 	

	
	//dogecoin shortcode
	
	function CDPdogecoin_shortcode() {
	
	global $wpdb;
	$dogewallet='';
	$dogecoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin'
                )
            );

		if ( $dogecoin > 0 ){ 
		$thedoge = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin' ) );
					
					if($thedoge->enable == 1){
						$dogewallet=$thedoge->wallet;
						$data = "<script src='".plugin_dir_url( __FILE__ )."/assets/js/qrious.js'></script>
			<script language='javascript'>
			function dogeshort(){
			var qr = new QRious({
				element: document.getElementById('qrdogecoinshortcode'),
				value: '".$dogewallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				});
			}
			window.onload = dogeshort;
			</script>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-shortcode{
				background-color:white;
				width:300px;
				height:310px;
				border-radius:5px;
				border:1px solid #999;
			}
			
			.hide-block{
				float:right;
				margin-right:5px;
				
			}
			
			.showme-title{
				width:100%;
				text-align:center;
				clear:both;
				font-family: 'K2D', sans-serif;
				text-transform:uppercase;
					font-size:14px;
			}
			
			.wallet-address{
				font-family: 'K2D', sans-serif;
				font-size:10px;
				border:solid 1px #999;
				background-color:#eee;
				border-radius:2px;
			}
			
			</style>
			<div class='donate-shortcode'>
			<div class='showme-title' >Wallet address of <span>dogecoin</span></div>
			<div class='wallet-address' style='text-align:center;font-size:10px;'>".$dogewallet."</div>
			<div class='qr-qode' style='text-align:center;'><canvas id='qrdogecoinshortcode'></canvas>
			</div>
			</div>
			
			";
        
					}else{
			$data = "";
		}
					
		}
    return $data;
	}
	
	//ethereum shortcode
	function CDPethereum_shortcode() {
	
	global $wpdb;
	$ethwallet='';
	$ethereum = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum'
                )
            );

		if ( $ethereum > 0 ){ 
		$theeth = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum' ) );
					
					if($theeth->enable == 1){
						$ethwallet=$theeth->wallet;
						$data = "<script src='".plugin_dir_url( __FILE__ )."/assets/js/qrious.js'></script>
			<script language='javascript'>
			function ethshort(){
			var qr = new QRious({
				element: document.getElementById('qrethereumshortcode'),
				value: 'bitcoin:".$ethwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
			}
			window.onload = ethshort;
			</script>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-shortcode{
				background-color:white;
				width:300px;
				height:310px;
				border-radius:5px;
				border:1px solid #999;
			}
			
			
			.showme-title{
				width:100%;
				text-align:center;
				clear:both;
				font-family: 'K2D', sans-serif;
				text-transform:uppercase;
					font-size:14px;
			}
			
			.wallet-address{
				font-family: 'K2D', sans-serif;
				font-size:10px;
				border:solid 1px #999;
				background-color:#eee;
				border-radius:2px;
			}
			
			</style>
			<div class='donate-shortcode'>
			<div class='showme-title' >Wallet address of <span>ethereum</span></div>
			<div class='wallet-address' style='text-align:center;font-size:10px;'>".$ethwallet."</div>
			<div class='qr-qode' style='text-align:center;'><canvas id='qrethereumshortcode'></canvas>
			</div>
			</div>
			
			";
        
					}else{
			$data = "";
		}
					
		}
    return $data;
	}
	
	
	//zcash shortcode
	function CDPzcash_shortcode() {
	
	global $wpdb;
	$zecwallet='';
	$zcash = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash'
                )
            );

		if ( $zcash > 0 ){ 
		$thezec = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash' ) );
					
					if($thezec->enable == 1){
						$zecwallet=$thezec->wallet;
						$data = "<script src='".plugin_dir_url( __FILE__ )."/assets/js/qrious.js'></script>
			<script language='javascript'>
			function zecshort(){
			var qr = new QRious({
				element: document.getElementById('qrzcashshortcode'),
				value: 'bitcoin:".$zecwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
			}
			window.onload = zecshort;
			</script>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-shortcode{
				background-color:white;
				width:300px;
				height:310px;
				border-radius:5px;
				border:1px solid #999;
			}
			
			
			.showme-title{
				width:100%;
				text-align:center;
				clear:both;
				font-family: 'K2D', sans-serif;
				text-transform:uppercase;
					font-size:14px;
			}
			
			.wallet-address{
				font-family: 'K2D', sans-serif;
				font-size:10px;
				border:solid 1px #999;
				background-color:#eee;
				border-radius:2px;
			}
			
			</style>
			<div class='donate-shortcode'>
			<div class='showme-title' >Wallet address of <span>zcash</span></div>
			<div class='wallet-address' style='text-align:center;font-size:10px;'>".$zecwallet."</div>
			<div class='qr-qode' style='text-align:center;'><canvas id='qrzcashshortcode'></canvas>
			</div>
			</div>
			
			";
        
					}else{
			$data = "";
		}
					
		}
    return $data;
	}
	
	
	
	
	function CDPextra_content ($content) {
		
		global $wpdb;
		
		
		$exist = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "activeposts
                    WHERE ID = 1 LIMIT 1", ''
                )
            );

		if ( $exist > 0 ){ 
		$donatetitle = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "activeposts
                    WHERE ID = 1 LIMIT 1", '' ) );
					
					
					
					
		
		//if is checked 
		// wyświetlanie i generowanie qrcode 5
		if($donatetitle->enable == 1){
			$counter=0;
			
			
			$btcwallet='3GPy46bhhitGL57vF8C4bUunxFrHNQmCFx';
			$bitcoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin'
                )
            );

		if ( $bitcoin > 0 ){ 
		$thebtc = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'bitcoin' ) );
					
					if($thebtc->enable == 1){
						$btcwallet=$thebtc->wallet;
					}
					
		}
		
		$dogewallet='';
		$dogetable="";
			$dogecoin = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin'
                )
            );

		if ( $dogecoin > 0 ){ 
		$thedoge = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'dogecoin' ) );
					
					if($thedoge->enable == 1){
						$dogewallet=$thedoge->wallet;
						$dogetable="<td style='border:none;'><img src='".plugin_dir_url( __FILE__ )."/img/big-doge.png' onclick='showmedoge();' style='cursor:pointer;'/></td>";
						$counter++;
					}
					
		}
		
		$ethwallet='';
		$ethtable="";
			$ethereum = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum'
                )
            );

		if ( $ethereum > 0 ){ 
		$theeth = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'ethereum' ) );
					
					if($theeth->enable == 1){
						$ethwallet=$theeth->wallet;
						$ethtable="<td style='border:none;'><img src='".plugin_dir_url( __FILE__ )."/img/big-eth.png' onclick='showmeeth();' style='cursor:pointer;'/></td>";
						$counter++;
					}
					
		}
		
		$zecwallet='';
		$zectable="";
			$zcash = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash'
                )
            );

		if ( $zcash > 0 ){ 
		$thezec = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "wallets
                    WHERE cryptocurrency = %s LIMIT 1", 'zcash' ) );
					
					if($thezec->enable == 1){
						$zecwallet=$thezec->wallet;
						$zectable="<td style='border:none;'><img src='".plugin_dir_url( __FILE__ )."/img/big-zec.png' onclick='showmezec();' style='cursor:pointer;'/></td>";
						$counter++;
					}
					
		}
		$tablewidth="50";
		if($counter==1){
			$tablewidth="100";
		}
		
		if($counter==2){
			$tablewidth="150";
		}
		
		if($counter==3){
			$tablewidth="200";
		}

			$extra_stuff = " <script src='".plugin_dir_url( __FILE__ )."/assets/js/qrious.js'></script>
			<script language='javascript'>
			function showmebtc(){
			if ((document.getElementById('showme').style.display == 'block'))
			{
			document.getElementById('showme').style.display = 'none';
			} else{
				document.getElementById('showme').style.display = 'block';
				document.getElementById('change-title').innerHTML = 'bitcoin';
				document.getElementById('change-wallet').innerHTML = '".$btcwallet."';
				var qr = new QRious({
				element: document.getElementById('qr'),
				value: 'bitcoin:".$btcwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
				
			}
			}
			
			function showmedoge(){
			if ((document.getElementById('showme').style.display == 'block'))
			{
			document.getElementById('showme').style.display = 'none';
			} else{
				document.getElementById('showme').style.display = 'block';
				document.getElementById('change-title').innerHTML = 'dogecoin';
				document.getElementById('change-wallet').innerHTML = '".$dogewallet."';	
				var qr = new QRious({
				element: document.getElementById('qr'),
				value: '".$dogewallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
				
				
			}
			}
			
			function showmeeth(){
			if ((document.getElementById('showme').style.display == 'block'))
			{
			document.getElementById('showme').style.display = 'none';
			} else{
				document.getElementById('showme').style.display = 'block';
				document.getElementById('change-title').innerHTML = 'ethereum';
				document.getElementById('change-wallet').innerHTML = '".$ethwallet."';
				var qr = new QRious({
				element: document.getElementById('qr'),
				value: 'ethereum:".$ethwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
				
			}
			}
			
			
			function showmezec(){
			if ((document.getElementById('showme').style.display == 'block'))
			{
			document.getElementById('showme').style.display = 'none';
			} else{
				document.getElementById('showme').style.display = 'block';
				document.getElementById('change-title').innerHTML = 'zcash';
				document.getElementById('change-wallet').innerHTML = '".$zecwallet."';
				var qr = new QRious({
				element: document.getElementById('qr'),
				value: 'zcash:".$zecwallet."',
				size: '250',
				background: 'white',
				foreground: 'black',
				padding: '20'
				})
				
			}
			}

			function hideme(){
			if ((document.getElementById('showme').style.display == 'block'))
			{
			document.getElementById('showme').style.display = 'none';
			} 
			}
			</script>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-title{
				font-family: 'K2D', sans-serif;
				font-weight:bold;
			}
			
			.donate-table{
				width:".$tablewidth."px;
				border:solid 1px #999;
				border-radius:5px;
			}

			</style>
			<div> <span class='donate-title'>".$donatetitle->text."</span> 
			<table class='donate-table'>
			<tr>
			<td style='border:none;'><img src='".plugin_dir_url( __FILE__ )."/img/big-btc.png' onclick='showmebtc();' style='cursor:pointer;'/></td>".$dogetable.$ethtable.$zectable."

			</tr>
			</table>
			<style>
			@import url('https://fonts.googleapis.com/css?family=K2D:300,400,700');
			.donate-show{
				position:fixed;
				z-index:99;
				display:none;
				margin:auto;
				left:0;
				right:0;
				bottom:0;
				top:0;
				background-color:white;
				width:400px;
				height:350px;
				border-radius:5px;
				border:1px solid #999;
			}
			
			.hide-block{
				float:right;
				margin-right:5px;
				
			}
			
			.showme-title{
				width:100%;
				text-align:center;
				clear:both;
				font-family: 'K2D', sans-serif;
				text-transform:uppercase;
			}
			
			.wallet-address{
				font-family: 'K2D', sans-serif;
				font-size:14px;
				border:solid 1px #999;
				background-color:#eee;
				border-radius:2px;
			}
			
			</style>
			<div class='donate-show' id='showme'>
			<div class='hide-block' onclick='hideme();'><img src='".plugin_dir_url( __FILE__ )."/img/close.png' style='cursor:pointer;'/></div>
			<div class='showme-title' >Wallet address of <span id='change-title'>bitcoin</span></div>
			<div class='wallet-address' id='change-wallet' style='text-align:center;'>13JDhP1YScpJosCbkXwWUeS</div>
			<div class='qr-qode' style='text-align:center;'><canvas id='qr'></canvas>
			</div>
			</div>
			</div>
			" ;
		}else{
			$extra_stuff = "" ;
		}
		
		
		}else{
			$extra_stuff = "" ;
		}
		
		
		
		
		return $content.$extra_stuff ;
	}
	
	
	function create_plugin_database_table()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "wallets";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

			$sql = "CREATE TABLE $table_name (
            ID mediumint(9) NOT NULL AUTO_INCREMENT,
            `cryptocurrency` varchar(255) NOT NULL,
            `wallet` varchar(255) NOT NULL,
            `enable` int(1) NOT NULL,
            PRIMARY KEY  (ID)
			)    $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	
		
		//tworzenie activebutton
		
		$table_name = $wpdb->prefix . "activeposts";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

			$sql = "CREATE TABLE $table_name (
            ID mediumint(9) NOT NULL AUTO_INCREMENT,
			`text` varchar(255) NOT NULL,
            `enable` int(1) NOT NULL,
            PRIMARY KEY  (ID)
			)    $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
		
		
		
		
		
		
	
	}
	
	
	
 
}
 
/*
 * Starts our plugin class, easy!
 */
new CDPCrypto();

?>
