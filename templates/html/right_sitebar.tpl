<script type="text/javascript" src="./templates/js/jquery.sticky-kit.min.js"></script>
<div class="right-sitebar">
<ul>
  <li> 
    <a href="./?task=playgrounds">${STR_PLAYGROUNDS}  
      <!-- IF '${NUMBERPLAYGROUNDS}' > '0' --><span>${NUMBERPLAYGROUNDS}</span><!-- END IF --> 
    </a>
    <!-- INCLUDE s_grounds.tpl -->
  </li>
  <li> 
    <a href="./?task=shops">${STR_SHOPS} 
      <!-- IF '${NUMBERSHOPS}' > '0' --><span>${NUMBERSHOPS}</span><!-- END IF --> 
    </a>
    <!-- INCLUDE s_shops.tpl -->
  </li>
  <li> 
    <a href="./?task=fitness">${STR_FITNESS}  
      <!-- IF '${NUMBERFITNESS}' > '0' --><span>${NUMBERFITNESS}</span><!-- END IF -->
    </a>
      <!-- INCLUDE s_feetness.tpl -->
  </li>
</ul>
</div>
<!-- IF '${OPEN_PAGE}' == '' -->
<script type="text/javascript">
  $(document).ready(function(){
    $('.right-sitebar').stick_in_parent({offset_top : 140});
  })
</script>
<!-- ELSE -->
<script type="text/javascript">
  $(document).ready(function(){
    $('.right-sitebar').stick_in_parent({offset_top : 70});
  })
</script>
<!-- END IF -->

		