<?php



/**
 * Description of calendar_list
 *
 * @author Mikko
 */
class calendar_list {
    #loginiin?
    private function is_user_logged (){
         session_start();
           if (isset($_SESSION['logged']) AND $_SESSION['logged']=='TRUE') {
            $this->GenerateCalendar('7', '', '8', '8');
            #Generoi kalenteri lista näkymä kirjautuneelle käyttäjälle
        } else {
            session_destroy();
            $this->GenerateCalendar('7', '', '8', '8');
        }
    }
    #osaksi calendaria?
    private function generate_calendar_list($logged){
          session_start();
          if ($logged){
              
          }else{
              #SQL hae kaikki listat
              while($rows <=$loops){
                  $kalenteri = "rows kalenterinimi";
                  echo '<p> rows kalenterinimi </p>
                <button type="button" onclick="ShowCalendar()" id="varaa" class="btn btn-primary btn-lg">Rows kalenteri</button> </div>';
              }
              
              
          }
       
      
    }
}
