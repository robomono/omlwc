<?php

//NEED NEW TABLE TO REGISTER INJURIES AND OUTS
//NEED NEW TABLE TO REGISTER SURFER RANK AT THE BEGGINING OF AN EVENT
	
class FSEvent{
	
	public function __construct(){
		
		session_start();
		//include_once(fsbasics.php);
		require_once("../config/db.php");
		
	}
	
	public function getAllUsersAndPicks(){
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->set_charset("utf8")) {$this->errors[] = $this->db_connection->error;}

		if (!$this->db_connection->connect_errno) {
			
			$sql = "SELECT * FROM users";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
					
				$users[$row['id']]['name'] = $row['name'];
				$users[$row['id']]['early'] = $row['early'];
				$names[$row['name']] = $row['id'];		
			}
			
		}
		
		if (!$this->db_connection->connect_errno) {
			
			$sql = "SELECT * FROM teams";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
					
				$teams[$row['id']]['team'] = $row['team'];
				$teams[$row['id']]['aka'] = $row['aka'];
				$teams[$row['id']]['group'] = $row['group'];		
			}
			
		}
		
		if (!$this->db_connection->connect_errno) {
			
			$sql = "SELECT * FROM predictions";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
					
				$predictions[$row['uid']]['a1'] = $row['A1']; 
				$predictions[$row['uid']]['a2'] = $row['A2']; 
				$predictions[$row['uid']]['a3'] = $row['A3']; 
				$predictions[$row['uid']]['a4'] = $row['A4'];
				
				$predictions[$row['uid']]['b1'] = $row['B1']; 
				$predictions[$row['uid']]['b2'] = $row['B2']; 
				$predictions[$row['uid']]['b3'] = $row['B3']; 
				$predictions[$row['uid']]['b4'] = $row['B4'];  
				
				$predictions[$row['uid']]['c1'] = $row['C1']; 
				$predictions[$row['uid']]['c2'] = $row['C2']; 
				$predictions[$row['uid']]['c3'] = $row['C3']; 
				$predictions[$row['uid']]['c4'] = $row['C4'];
				
				$predictions[$row['uid']]['d1'] = $row['D1']; 
				$predictions[$row['uid']]['d2'] = $row['D2']; 
				$predictions[$row['uid']]['d3'] = $row['D3']; 
				$predictions[$row['uid']]['d4'] = $row['D4'];    
				
				$predictions[$row['uid']]['e1'] = $row['E1']; 
				$predictions[$row['uid']]['e2'] = $row['E2']; 
				$predictions[$row['uid']]['e3'] = $row['E3']; 
				$predictions[$row['uid']]['e4'] = $row['E4']; 
				
				$predictions[$row['uid']]['f1'] = $row['F1']; 
				$predictions[$row['uid']]['f2'] = $row['F2']; 
				$predictions[$row['uid']]['f3'] = $row['F3']; 
				$predictions[$row['uid']]['f4'] = $row['F4']; 
				
				$predictions[$row['uid']]['g1'] = $row['G1']; 
				$predictions[$row['uid']]['g2'] = $row['G2']; 
				$predictions[$row['uid']]['g3'] = $row['G3']; 
				$predictions[$row['uid']]['g4'] = $row['G4']; 
				
				$predictions[$row['uid']]['h1'] = $row['H1']; 
				$predictions[$row['uid']]['h2'] = $row['H2']; 
				$predictions[$row['uid']]['h3'] = $row['H3']; 
				$predictions[$row['uid']]['h4'] = $row['H4']; 
						
			}
			
		}
		
		
		
		
		foreach($users as $uid=>$v){
			
			$toreturn.= '<div class="grid-x align-center">
							<div class="cell large-12 small-12" style="border:1px solid blue;">'
								.$v["name"].
							'</div>
						</div>';
			
			
			$r = 0;
			$c = 0;
			
			foreach($predictions[$uid] as $x=>$pid){
				
				
					
				if($r==0){
					$toreturn.= $teams[$pid]['aka'];
					$r++;
				}else if($r>0 && $r<3){
					$toreturn.= " - " .$teams[$pid]['aka'];
					$r++;
				}else{
					$toreturn.= " - " .$teams[$pid]['aka'] ."</br>";
					$r=0;
					if($c==0){$toreturn.= "-</br>";$c=1;}
					elseif($c==1){$toreturn.= "-----</br></br>";$c=0;}
				}
					
					
			}
			

		}
		
		return $toreturn;
	}
	
	
	
	
	public function getEventStatus($event_id){
		
		$points[2] = 500;
		$points[3] = 1750;
		$points[5] = 4000;
		$points[6] = 5200;
		$points[7] = 6500;
		$points[8] = 8000;
		
		$rank[2] = 25;
		$rank[3] = 13;
		$rank[5] = 9;
		$rank[6] = 5;
		$rank[7] = 3;
		$rank[8] = 2;
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->set_charset("utf8")) {
			$this->errors[] = $this->db_connection->error;
		}

		if (!$this->db_connection->connect_errno) {

			//---GET ROUND
			$sql = "SELECT e.name,e.status,e.nowsurfing, h.round, h.heat, h.player, h.surfer_id, h.result, h.jersey
					FROM events AS e
					LEFT JOIN heats AS h
					ON e.id = h.event_id
					WHERE e.id=$event_id
					ORDER BY h.round,h.heat,h.result,h.player";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
				$eventstatus = $row['status'];
				$eventname = $row['name'];
				$currentroundandheat = $row['nowsurfing'];
				
				$event[$row['round']][$row['heat']][$row['player']]['sid'] = $row['surfer_id'];
				$event[$row['round']][$row['heat']][$row['player']]['rnk'] = $row['result'];
				$event[$row['round']][$row['heat']][$row['player']]['jer'] = $row['jersey'];
				
				//records last registered round and heat for surfer
				$nextheat['round'][$row['surfer_id']] = $row['round'];
				$nextheat['heat'][$row['surfer_id']] = $row['heat'];
				
				//records w/l/r/ww/u per round
				if($row['round']==1 || $row['round']==3){
					if($row['result']==1){
						$roundresults[$row['surfer_id']][$row['round']] = 12;
						$roundresults[$row['surfer_id']][($row['round']+1)] = 11;
					}
					else if($row['result']==2 || $row['result']==3){
						$roundresults[$row['surfer_id']][$row['round']] = 22;
					}else if($row['result']==0){
						$roundresults[$row['surfer_id']][$row['round']] = 0;
					}
				}
				else if($row['round']!=1 && $row['round']!=3){
					if($row['result']==1){
						$roundresults[$row['surfer_id']][$row['round']] = 12;
					}
					else if($row['result']==2){
						$roundresults[$row['surfer_id']][$row['round']] = 33;
					}else if($row['result']==0){
						$roundresults[$row['surfer_id']][$row['round']] = 0;
					}
				}
				
				//records point score if surfer scored 2
				if(($row['round']!=1 && $row['round']!=4) && $row['result']==2){
					$score[$row['surfer_id']]['pts'] = $points[$row['round']];
					$score[$row['surfer_id']]['rnk'] = $rank[$row['round']];
				}
				else if($row['round']==8 && $row['result']==1){
					$score[$row['surfer_id']]['pts'] = 10000;
					$score[$row['surfer_id']]['rnk'] = 1;
				}

			}
			//---END GET ROUND
			
			$return['status'] = $eventstatus;					//event status
			$return['name'] = $eventname;							//name
			$return['current'] = $currentroundandheat;//current (or last registered) round and heat
			$return['rounds'] = $event;								//allrounds
			$return['nextheat'] = $nextheat;					//next heat per surfer
			$return['score'] = $score;								//score per surfer
			$return['roundresults'] = $roundresults;	//result per surfer per round
			
		}//connection errors
		
		return $return;
		
	}
	
	public function getSurfers(){
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->set_charset("utf8")) {
			$this->errors[] = $this->db_connection->error;
		}

		if (!$this->db_connection->connect_errno) {

			//---GET ROUND
			$sql = "SELECT id,name,img,aka,wildcard,for_event FROM surfers";

			$result = $this->db_connection->query($sql);
		
			while($row = mysqli_fetch_array($result)){
				$surfers[$row['id']]['name'] = $row['name'];
				$surfers[$row['id']]['aka'] = $row['aka'];
				$surfers[$row['id']]['img'] = $row['img'];
				$surfers[$row['id']]['wc'] = $row['wildcard'];
				$surfers[$row['id']]['for_event'] = $row['for_event'];
			}
			//---END GET ROUND
		}
		
		return $surfers;
		
	}
	
	public function getPicks($event_id,$league_id){
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->set_charset("utf8")) {
			$this->errors[] = $this->db_connection->error;
		}

		if (!$this->db_connection->connect_errno) {

			//---GET ROUND
			$sql = "SELECT p.user_id,p.pick_id,p.status,p.active,p.wc,u.name,u.team,u.short 
					FROM league_picks p
					LEFT JOIN league_control AS u
					ON p.user_id = u.user_id
					WHERE p.event=$event_id AND p.league_id=$league_id AND u.league_id=$league_id AND p.active<=7
					ORDER BY p.pick_id";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
				$picks[$row['pick_id']][] = $row['user_id'];
				$pick_header[$row['pick_id']] .= " has-".$row['user_id'];
				
				$users[$row['user_id']]['name'] = $row['name'];
				$users[$row['user_id']]['short'] = strtoupper($row['short']);
				$users[$row['user_id']]['shortname'] = explode(" ",$row['name'])[0];
				$users[$row['user_id']]['team'] = $row['team'];
			}
			//---END GET ROUND
		}
		
		$toreturn['picks'] = $picks;
		$toreturn['headers'] = $pick_header;
		$toreturn['users'] = $users;
		
		return $toreturn;
		
	}
	
	private function buildEventMenu($eventdata,$event_id,$user_id){
		
		$event_status = $eventdata['status'];
		
		if($event_status==0){
			//upcoming event
			$navmenu = '<div class="grid-x align-center navmenu idleeventnav">
							<div class="cell large-4 small-4 selected">Team</div>
							<div class="cell large-4 small-4">Waivers</div>
							<div class="cell large-4 small-4">Leaderboard</div>
						</div>';
			
		}elseif($event_status==1){
			//idle event, waiver request
			$navmenu='
				<div class="grid-x align-center navmenu idleeventnav">
					<div class="cell large-4 small-4 selected">Team</div>
					<div class="cell large-4 small-4">Waivers</div>
					<div class="cell large-4 small-4">Leaderboard</div>
				</div>
			';
			
		}elseif($event_status==2){
			//idle event, waiver open
			$navmenu='
				<div class="grid-x align-center navmenu idleeventnav">
					<div class="cell large-4 small-4 selected">Team</div>
					<div class="cell large-4 small-4">Waivers</div>
					<div class="cell large-4 small-4">Leaderboard</div>
				</div>
			';
			
		}elseif($event_status==3){
			//live event
			$navmenu='
				<div class="grid-x align-center navmenu activeeventnav">
					<div class="cell large-4 small-4 selected">Live</div>
					<div class="cell large-4 small-4">Team</div>
					<div class="cell large-4 small-4">Standings</div>
				</div>
				
				<div class="grid-x align-center roundnav">
					<div class="cell medium-2 small-2"><a href="#" id="roundback"><i class="material-icons">chevron_left</i></a></div>
		
					<div class="cell medium-2 small-8 roundselect selected-round" id="menu-round1">Round 1</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round2">Round 2</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round3">Round 3</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round4">Round 4</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round5">Round 5</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round6">Quarterfinal</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round7">Semifinal</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round8">Final</div>
		
					<div class="cell medium-2 small-2"><a href="#" id="roundnext"><i class="material-icons">chevron_right</i></a></div>
				</div>
				';
			
		}elseif($event_status==4){
			//finished event
			$navmenu='
				<div class="grid-x align-center navmenu finishedeventnav">
					<div class="cell large-4 small-4 selected">Rounds</div>
					<div class="cell large-4 small-4"><a href="teams.php?eid='.$event_id.'">Team</a></div>
					<div class="cell large-4 small-4"><a href="standings.php?eid='.$event_id.'">Standings</a></div>
				</div>
				
				<div class="grid-x align-center navmenu leaderboardnav hidden">
					<div class="cell large-6 small-6 selected">Fantasy League</div>
					<div class="cell large-6 small-6">World Surf League</div>
				</div>
				
				<div class="grid-x align-center roundnav">
					<div class="cell medium-2 small-2"><a href="#" id="roundback"><i class="material-icons">chevron_left</i></a></div>
		
					<div class="cell medium-2 small-8 roundselect selected-round" id="menu-round1">Round 1</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round2">Round 2</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round3">Round 3</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round4">Round 4</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round5">Round 5</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round6">Quarterfinal</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round7">Semifinal</div>
					<div class="cell medium-2 small-8 roundselect" id="menu-round8">Final</div>
		
					<div class="cell medium-2 small-2"><a href="#" id="roundnext"><i class="material-icons">chevron_right</i></a></div>
				</div>
			';
		}
		
		return $navmenu;
		
	}
	
	private function buildHeatHeaders($rounds,$picks,$users){
		
		//picks = 
		//[surferid] => [0] => [userid]
		//[surferid] => [1] => [userid]
		//goes up to [4] bc a surfer can only fit in a 
		
		//create array with all users (to figure out if user has no picks in round)
		foreach($users as $uid=>$v){
			$userlist[$uid] = 0;
			$headers[100] .= " has-$uid"; //a list of all users for rounds that havent been filled yet
		}
		
		//first navigates $rounds: round -> heat -> player to get *surferid*
		//then gets that *surferid* and uses $picks get *numberofusers* that have picked that surfer id
		//runs that *numberofteams* on loop to get each of the *userid* that picked that *surferid* and inserts into *header* array
		
		foreach($rounds as $round=>$v1){
			
			//get a new list of users every round to rule out users with no pick this round
			$allusers = $userlist; 
			
			foreach($v1 as $heat=>$v2){
				foreach($v2 as $player=>$v3){
					
					//build "has" header for filtering rounds by username					
					for($i=0;$i<sizeof($picks[$v3['sid']]);$i++){
						$headers[$round][$heat]['has'] .= " has-".$picks[$v3['sid']][$i];
						$allusers[$picks[$v3['sid']][$i]]++;
					}
					
					//build round header and row type for surfers that are scored
					if($v3['sco']!=0){
						
						$headers[$round][$heat]['typ'] = "round".$round."complete";
						
						if($v3['sco']==1){
							$headers[$round][$heat]['res'][$player] = "<div class='grid-x heatwinner eventheatrow'>";
						}
						elseif($round!=1 && $round!=4 && $v3['sco']==2){
							$headers[$round][$heat]['res'][$player] = "<div class='grid-x rd".$round."loser eventheatrow'>";
						}
						elseif(($round==1 || $round==4) && $v3['sco']==2){
							$headers[$round][$heat]['res'][$player] = "<div class='grid-x heatrelegated eventheatrow'>";
						}
						elseif($v3['sco']==3){
							$headers[$round][$heat]['res'][$player] = "<div class='grid-x heatrelegated eventheatrow'>";
						}
					}
					
					//build round header and row type (jersey) for surfers that aren't scored
					else if($v3['sco']==0){
						
						$headers[$round][$heat]['typ'] = "round".$round."unsurfed";
						
						if($v3['jer']=="r"){$headers[$round][$heat]['res'][$player]		  = "<div class='grid-x redjersey eventheatrow'>";}
						elseif($v3['jer']=="b"){  $headers[$round][$heat]['res'][$player] = "<div class='grid-x bluejersey eventheatrow'>";}
						elseif($v3['jer']=="w"){  $headers[$round][$heat]['res'][$player] = "<div class='grid-x whitejersey eventheatrow'>";}
						elseif($v3['jer']=="y"){  $headers[$round][$heat]['res'][$player] = "<div class='grid-x yellowjersey eventheatrow'>";}
						elseif($v3['jer']=="wr"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x wredjersey eventheatrow'>";}
						elseif($v3['jer']=="wb"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x wbluejersey eventheatrow'>";}
						elseif($v3['jer']=="bb"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x bbluejersey eventheatrow'>";}
						elseif($v3['jer']=="br"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x bredjersey eventheatrow'>";}
						elseif($v3['jer']=="wy"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x wyellowjersey eventheatrow'>";}
						elseif($v3['jer']=="by"){ $headers[$round][$heat]['res'][$player] = "<div class='grid-x byellowjersey eventheatrow'>";}
						else{					  $headers[$round][$heat]['res'][$player] = "<div class='grid-x nosetjersey eventheatrow'>";}
						
					}
					
				}
			}
			
			//goes through user counter to find out if users have no picks in this round
			foreach($allusers as $uid=>$count){
				
				//0 count means no picks were counted for this user when generating headers in this round
				if($count==0){
					$headers[$round]['emp'] .= " has-$uid";
				}
				
			}
			
		}

		return $headers;
		
	}
	
	private function buildSurferPicks($surfers,$users,$picks){
		
		foreach($picks as $sid=>$v1){
			
			if(!empty($picks[$sid][0])){
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick is-".$picks[$sid][0]."'>".$users[$picks[$sid][0]]['short']."</div>";
			}else{
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick nopicks'></div>";
			}
			
			if(!empty($picks[$sid][1])){
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick is-".$picks[$sid][1]."'>".$users[$picks[$sid][1]]['short']."</div>";
			}else{
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick nopicks'></div>";
			}
			
			if(!empty($picks[$sid][2])){
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick is-".$picks[$sid][2]."'>".$users[$picks[$sid][2]]['short']."</div>";
			}else{
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick nopicks'></div>";
			}
			
			if(!empty($picks[$sid][3])){
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick is-".$picks[$sid][3]."'>".$users[$picks[$sid][3]]['short']."</div>";
			}else{
				$surfers[$sid]['pickcell'] .= "<div class='small-3 cell eventpick nopicks'></div>";
			}
				
			
			
		}
		
		return $surfers;
		
	}
	
	private function buildFilterMenu($users){
		
		$filtermenu.='
			<div class="grid-x align-center filter-menu">
			<div class="large-10 medium-12 small-12 cell" id="selectedfilter">Showing: All <i class="material-icons">chevron_left</i> </div>
			<div class="large-10 medium-12 small-12 cell heat-filter-select" id="selectall">All</div>';
			
			foreach($users as $uid=>$v){
				
				$filtermenu.='<div class="large-10 medium-12 small-12 cell heat-filter-select" id="select'.$uid.'">'.$v['short'].'</div>';
			}	
			
			$filtermenu.='</div>';
			
			return $filtermenu;
		
	}
	
	private function displayFinishedRounds($rounds,$surfers,$picks,$users,$headers){
		
		for($i=1;$i<=8;$i++){
			
			$toreturn.= "<div class='roundcontainer hiddenround' id='r".$i."'>"; 
			
			//rounds that have data
			if(!empty($rounds[$i])){
				
				//display all rounds
				foreach($rounds[$i] as $heat=>$v2){

					$toreturn.= "<div class='grid-x align-center eventrounddetails ".$headers[$i][$heat]['has']."' id='e1h".$heat."'>";
					$toreturn.= "<div class='large-10 medium-12 small-12 cell eventheattitle ".$headers[$i][$heat]['typ']."'>Heat ".$heat."</div>";
					$toreturn.= "<div class='large-10 medium-12 small-12 cell'>";

					foreach($v2 as $player=>$v3){

						$sid = $v3['sid'];

						$toreturn.= $headers[$i][$heat]['res'][$player];
						$toreturn.="<div class='large-3 medium-4 cell eventsurfer hide-for-small-only'>".$surfers[$sid]['name']."</div>
									<div class='small-2 cell eventsurfershort show-for-small-only'>".$surfers[$sid]['aka']."</div>";

						$toreturn.="<div class='large-9 medium-8 small-10 cell eventpicklist'>
										<div class='grid-x is-collapse-child'>".$surfers[$sid]['pickcell']."</div>
									</div>";

						$toreturn.="</div>";//ends grid-x row $headers[$round][$heat]['res'][$player]

					}

					$toreturn .= "</div></div>";//ends row grid-x for each heat
				}
				
				//create container for users with no picks this round
				if(!empty($headers[$i]['emp'])){
					
					$toreturn.='
					
						<div class="grid-x align-center eventrounddetails emptypicks '.$headers[$i]['emp'].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell">No picks in this round</div>
						</div>
						
					';
					
				}
				
			}
			
			//rounds with no data
			elseif(empty($rounds[$i])){
				
				if($i==1){
					
					$toreturn.= '
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 2</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 3</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 4</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>

									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 5</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 6</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 7</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 8</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 9</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 10</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 11</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 12</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									';
					
				}
				
				elseif($i==2 || $i==3){
					
					$toreturn.='
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 2</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 3</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 4</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 5</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 6</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 7</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 8</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 9</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 10</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 11</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 12</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
					';
					
				}
				
				elseif($i==4){
					
					$toreturn.= '
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 2</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 3</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>
									
									<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
										<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 4</div>
										<div class="large-10 medium-12 small-12 cell">
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
											<div class="grid-x nosetjersey eventheatrow">
												<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
												<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
												<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
											</div>
										</div>
									</div>';
				}
				
				elseif($i==5 || $i==6){
					
					$toreturn.= '
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 2</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 3</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 4</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
					';
					
				}
				
				elseif($i==7){
					
					$toreturn.='
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						
						<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 2</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
					';
					
				}
				
				elseif($i==8){
					
					$toreturn.='<div class="grid-x align-center eventrounddetails'.$headers[100].'" id="e1h1">
							<div class="large-10 medium-12 small-12 cell eventheattitle round4unsurfed">Heat 1</div>
							<div class="large-10 medium-12 small-12 cell">
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
								<div class="grid-x nosetjersey eventheatrow">
									<div class="large-3 medium-4 cell eventsurfer hide-for-small-only">TBD</div>
									<div class="small-2 cell eventsurfershort show-for-small-only">TBD</div>
									<div class="large-9 medium-8 small-10 cell eventpicklist"> </div>
								</div>
							</div>
						</div>
						';
				}
				
			}
			
			$toreturn.= "</div>";//ends round countainer
		}
		
		
		return $toreturn;
		
	}
	
	public function getAllRounds($event_id,$user_id){
		
		$user_id = 104; //<------------------------------eventually remove and use session id
		$league_id = 1;//<------------------------------CHANGE LEAGUE ID
			
		$eventdata = $this->getEventStatus($event_id);
		$surfers = $this->getSurfers();
		$allpicks = $this->getPicks($event_id,$league_id);
		
		$picks = $allpicks['picks'];
		$users = $allpicks['users'];
		
		$surfers 	= $this->buildSurferPicks($surfers,$users,$picks);
				
		$event_name = 	$eventdata['name'];
		$event_status = $eventdata['status'];
		$rounds = 		$eventdata['rounds'];

		if($event_status==4){
			//finished event
			$filtermenu = $this->buildFilterMenu($users);
			
			$navmenu = $this->buildEventMenu($eventdata,$event_id,$user_id);
			
			$headers 		= $this->buildHeatHeaders($rounds,$picks,$users);
			$displayrounds 	= $this->displayFinishedRounds($rounds,$surfers,$picks,$users,$headers);
			
			$display['nav']	 = $navmenu;
			$display['menu'] = $filtermenu;
			$display['main'] = $displayrounds;
			
		}
		elseif($event_status==3){
			//live event
			$navmenu = $this->buildEventMenu($eventdata,$event_id,$user_id);
			
			$filtermenu = $this->buildFilterMenu($users);
			$headers 	= $this->buildHeatHeaders($rounds,$picks,$users);
			
			$rounds 	= $this->displayFinishedRounds($rounds,$surfers,$picks,$users,$headers);
			
			$display['nav']	 = $navmenu;
			$display['menu'] = $filtermenu;
			$display['main'] = $rounds;
		
		}
		elseif($event_status==2){
			//lineups open - free waivers
			$navmenu = $this->buildEventMenu($eventdata,$event_id,$user_id);
			
			$display['nav']	 = $navmenu;
			
		}
		elseif($event_status==1){
			//lineups open - waiver period
			$navmenu = $this->buildEventMenu($eventdata,$event_id,$user_id);
			
			$display['nav']	 = $navmenu;
		}
		elseif($event_status==0){
			//upcoming event
			$navmenu = $this->buildEventMenu($eventdata,$event_id,$user_id);
			
			$display['nav']	 = $navmenu;
		}
		
		//return $display;
		
		return $display;
		
	}
	
}//end class FSEvent
	
?>