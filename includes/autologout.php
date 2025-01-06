<?php

if ($session) {
   $time_logout = $session['exp'] - time();
   $seconds_waiting = 30;
?>
   <script type="module">
      import {
         showAutoLogout
      } from "@util";

      const secondsWaiting = <?= $seconds_waiting; ?>;

      <?php
      if ($time_logout <= $seconds_waiting) {
      ?>
         showAutoLogout({
            secondsWaiting: <?= $time_logout; ?>
         })
      <?php
      } else {
      ?>
         setTimeout(() => {
            showAutoLogout({
               secondsWaiting
            })
         }, <?= ($time_logout - $seconds_waiting) * 800 ?>);
      <?php
      }
      ?>
   </script>
<?php
}
?>