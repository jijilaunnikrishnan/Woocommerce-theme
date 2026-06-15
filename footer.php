<?php
	/*-----------------------------------------------------------------------------------*/
	/* This template will be called by all other template files to finish 
	/* rendering the page and display the footer area/content
	/*-----------------------------------------------------------------------------------*/



    switch( pll_current_language()){
        case "de":
            $aktuelles = esc_url( get_permalink(80) );
            $ueberuns = esc_url( get_permalink(82) );
            $kontakt = esc_url( get_permalink(84) );
            $datenschutz = esc_url( get_permalink(63) );
            $impressum = esc_url( get_permalink(64) );
            $agb = esc_url( get_permalink(66) );
            break;
        case "fr":
            $aktuelles = esc_url( get_permalink(121169) );
            $ueberuns = esc_url( get_permalink(70348) );
            $kontakt = esc_url( get_permalink(121171) );
            $datenschutz = esc_url( get_permalink(121177) );
            $impressum = esc_url( get_permalink(121182) );
            $agb = esc_url( get_permalink(66) );
            break;
        case "en":
            $aktuelles = esc_url( get_permalink(439013) );
            $ueberuns = esc_url( get_permalink(440697) );
            $kontakt = esc_url( get_permalink(439627) );
            $datenschutz = esc_url( get_permalink(439644) );
            $impressum = esc_url( get_permalink(439906) );
            $agb = esc_url( get_permalink(66) );
            break;
    }
?>




<footer class="page-foot">
        <div class="container">
            <ul class="menu align-center">
                <li><a href="<?php echo $datenschutz; ?>"><?php pll_e("Datenschutz"); ?></a></li>
                <li><a href="<?php echo $impressum; ?>"><?php pll_e("Impressum"); ?></a></li>
                
                <li class="mobile">
                    <a  href="<?php echo $aktuelles; ?>"><?php pll_e("Aktuelles"); ?></a>
                </li>

                <li class="mobile">
                    <a  href="<?php echo $ueberuns; ?>"><?php pll_e("Über uns"); ?></a>
                </li>

                <li class="mobile">
                    <a  href="<?php echo $kontakt; ?>"><?php pll_e("Kontakt"); ?></a>
                </li>
            </ul>
        </div>
    </footer>




<?php wp_footer(); 
// This fxn allows plugins to insert themselves/scripts/css/files (right here) into the footer of your website. 
// Removing this fxn call will disable all kinds of plugins. 
// Move it if you like, but keep it around.
?>

</body>
</html>
