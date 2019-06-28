        </article>

<footer>
  <div id="footer-container">
     
      <p class="footer-title">Digital Indipetae Database</p>
      <p>The Digital Indipetae Database is organized by the Institute for Advanced Jesuit Studies in collaboration with the Archivum Romanum Societatis Iesu. Its editorial and scientific boards coordinate the project and promote it within the international community of scholars.</p>
      
    <div class="row">
        <div class="col-md-3 col-sm-12">
          <img src="/themes/minimalist/img/Logo_300.png" class="footer-logo">
        </div>
        
        <div class="col-md-9 col-sm-12">
          <p>The Digital Indipetae Database was developed and is maintained in cooperation with Boston College Libraries. Learn more about the <a href="/team">development team</a> and process.</p>
        </div>
    </div>
    
    <div id="footer-menu">
        <ul class="site-links">
          <li><a href="/about">About</a></li>
          <li><a href="/collaborate">Learn More</a></li>
          <li><a href="/contact">Contact</a></li>
        </ul>
    </div>
    
    <div class='copyright'>
            <p>Copyright Trustees of Boston College</p>
    </div>
    
  </div>
<!-- end wrap -->
</footer>

    <script>

    jQuery(document).ready(function() {

        Omeka.showAdvancedForm();
        Omeka.skipNav();
        Omeka.megaMenu('#top-nav');
    });
    </script>

    <script type="text/javascript">
    jQuery(document).ready(function(){
        allimg = jQuery("#itemfiles-nav img")
        if(allimg && allimg.length < 2){
           jQuery(".chocolat-right, .chocolat-left").hide()
        }

        jQuery(".element-set .element:not(:has(>.element-text))").each(function() {
            jQuery(this).addClass("element-hide")
        })
       
    });
  </script>

</body>
</html>
