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
      const timeLogout = <?= $time_logout; ?>;

      if (timeLogout <= secondsWaiting) {
         showAutoLogout({
            secondsWaiting: timeLogout
         });
      } else {
         setTimeout(() => {
            showAutoLogout({
               secondsWaiting
            });
         }, (timeLogout - secondsWaiting) * 900);
      }
   </script>
<?php
}
?>