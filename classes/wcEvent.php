<?php

//NEED NEW TABLE TO REGISTER INJURIES AND OUTS
//NEED NEW TABLE TO REGISTER SURFER RANK AT THE BEGGINING OF AN EVENT
	
class FSEvent{
	
	public function __construct(){
		
		session_start();
		//include_once(fsbasics.php);
		require_once("../config/db.php");
		
	}
	
	public function displayFinalResults(){
		
		$data = $this->calculateUserPoints();
		
		$users = $data['users'];
		$leaderboard = $data['leaderboard'];
		$teams = $data['teams'];
		$results = $data['results'];
		$groups = $data['groups'];
		
		foreach($users as $uid=>$v){$sortedlb[$uid] = $users[$uid]['totalpoints'];}
		arsort($sortedlb);
		$x=1;
		$tr.="<div class='grid-container' style='margin-top:25px;'><div class='grid-x'><div class='large-12 columns'>";
		foreach($sortedlb as $uid=>$score){
			
			$tr.= "<div class='userscorerow grid-x'>
					<div class='userscoreposition'>$x</div>
					<div class='userscorename' id='goto".$uid."'><a href='#u".$uid."'>".ucfirst($users[$uid]['name'])."</a></div>
				 	<div class='userscorescore'>".$score."</div>
				</div>";
				$x++;
		}
		$tr.="</div></div></div>";
		
		
		foreach($users as $uid=>$v){
			
			$tr.= "<a name='u".$uid."'></a>";
			
			$tr.="<div class='grid-container usergrid gridu".$uid."' style='border:3px solid #f2f2f2;margin-top:25px;padding:0px;'>";
			
			$tr.= "<div class='grid-x'>
						<div class='small-11 cell'><div class='nametitle'> " .strtoupper($users[$uid]['name'])."</div></div>
						<div class='small-1 cell closename' id='close".$uid."'> <i class='material-icons'>clear</i> </div>
					</div>";
			
			$tr.= "<div class='grid-x'>";
			
			for($g=0;$g<=7;$g++){
				
				$tr.= "<div class='large-6 small-12 cell'>";
				
				$tr.= "<div class='grouptitle'> GROUP " .$groups[$g] ."</div>";
				
				$tr.= "<div class='groupheaders grid-x'>
							<div class='small-6 cell standingsheader'>S T A N D I N G S</div>
							<div class='small-6 cell picksheader'>P I C K S</div>
						</div>";
				
				
				
				for($p=0;$p<=3;$p++){
					
					$tr.="<div class='grid-x tablerow level".$p."'>
							
							<div class='rowflag small-2 cell' style='border-left: 1px solid #d9d9d9;'>
								<img src='img/flags1/a" .$teams[$leaderboard[$g][$p]]['aka'] .".png'>
							</div>
							
							<div class='rowaka small-2 cell '>".$teams[$leaderboard[$g][$p]]['aka']."</div>
							
							<div class='rowstandingspts small-2 cell'>".$results[$leaderboard[$g][$p]]['pts']."</div>
							
							<div class='rowflag small-2 cell ".$users[$uid]['status'][$g][$p]."'>
								<img src='img/flags1/a" .$teams[$users[$uid]['pick'][$g][$p]]['aka'] .".png'>
							</div>
							
							<div class='rowaka small-2 cell  ".$users[$uid]['status'][$g][$p]."'>".$teams[$users[$uid]['pick'][$g][$p]]['aka']."</div>
							
							<div class='rowuserpts small-2 cell ".$users[$uid]['status'][$g][$p]."'>".$users[$uid]['points'][$g][$p]."</div>
							
							
						</div>";
					
				}
				
				$tr.= "<div class='rowgrouppoints'>" .$users[$uid]['gpoints'][$g]." pts</div>";
				
				$tr.= "</div>";//end large-6 small-12 cell for each group
				
				if(($g!=0)&&($g%2!=0)){$tr.="</div> <div class='grid-x'>";}//closes grid container every 2 groups
				
			}
			
			$tr.= "<div class='rowusertotalpoints'>Total: " .$users[$uid]['totalpoints']." pts</div>";
			
			$tr.= "<div style='width:100%;padding:10px;font-size:10pt;text-align:center;'><a href='#'>Back to leaderboard</a></div>";
			
			$tr.= "</div></div>";//end last group container and ends zurb container
			
		}
		
		
		
		
		return $tr;
	}
	
	public function calculateUserPoints(){
		
		$users = $this->getAllUsersAndPicks();
		
		$data = $this->getAllTeamsAndScores();		
		$leaderboard = $data['leaderboard'];
		$teams = $data['teams'];
		$results = $data['results'];
		
		$groups = $this->getGroupAlphabet();
		
//		$tr.= "GROUP " .$groups[0] ."</br>";
//		$tr.= $users[1]['pick'][0][0] ." -- " .$leaderboard[0][0] ."</br>";
//		$tr.= $users[1]['pick'][0][1] ." -- " .$leaderboard[0][1] ."</br>";
//		$tr.= $users[1]['pick'][0][2] ." -- " .$leaderboard[0][2] ."</br>";
//		$tr.= $users[1]['pick'][0][3] ." -- " .$leaderboard[0][3] ."</br></br>";
		
//		$tr.= "GROUP " .$groups[1] ."</br>";
//		$tr.= $users[1]['pick'][1][0] ." -- " .$leaderboard[1][0] ."</br>";
//		$tr.= $users[1]['pick'][1][1] ." -- " .$leaderboard[1][1] ."</br>";
//		$tr.= $users[1]['pick'][1][2] ." -- " .$leaderboard[1][2] ."</br>";
//		$tr.= $users[1]['pick'][1][3] ." -- " .$leaderboard[1][3] ."</br></br>";
		
//		$tr.= "Analyzing matches... </br>";
		
		foreach($users as $uid=>$v){
			
//			$tr.= "-- User $uid --</br>";
			
			for($g=0;$g<=7;$g++){
				
//				$tr.= "Group " .$groups[$g] ."</br>";
				
				for($p=0;$p<=3;$p++){
					
//					$tr.= "$p - ";
					
					if($p==0){
						//FIRST PICK CHECK
						if($users[$uid]['pick'][$g][0] == $leaderboard[$g][0]){
							$pts += 2;
							$users[$uid]['status'][$g][0] = "bullseye";
							$users[$uid]['points'][$g][0] = "+2 pts";
//							$tr.= "bullseye";
						}elseif($users[$uid]['pick'][$g][0] == $leaderboard[$g][1]){
							$pts += 1;
							$users[$uid]['status'][$g][0] = "indirecthit";
							$users[$uid]['points'][$g][0] = "+1 pt";
//							$tr.= "indirecthit";
						}else{
							$users[$uid]['status'][$g][0] = "totalmiss";
							$users[$uid]['points'][$g][0] = " ";
//							$tr.= "miss";
						}						
					}//end pick 0 check
					
					else if($p==1){
						//SECONG PICK CHECK
						if($users[$uid]['pick'][$g][1] == $leaderboard[$g][1]){
							$pts += 2;
							$users[$uid]['status'][$g][1] = "bullseye";
							$users[$uid]['points'][$g][1] = "+2 pts";
//							$tr.= "bullseye";
						}elseif($users[$uid]['pick'][$g][1] == $leaderboard[$g][0]){
							$pts += 1;
							$users[$uid]['status'][$g][1] = "indirecthit";
							$users[$uid]['points'][$g][1] = "+1 pt";
//							$tr.= "indirecthit";
						}else{
							$users[$uid]['status'][$g][1] = "totalmiss";
							$users[$uid]['points'][$g][1] = " ";
//							$tr.= "miss";
						}
					}//end of pick 1 check
					
					else if($p==2){
						//SECONG PICK CHECK
						if($users[$uid]['pick'][$g][2] == $leaderboard[$g][2]){
							$pts += 0.5;
							$users[$uid]['status'][$g][2] = "tinybullseye";
							$users[$uid]['points'][$g][2] = "+0.5 pts";
//							$tr.= "bullseye";
						}else{
							$users[$uid]['status'][$g][2] = "totalmiss";
							$users[$uid]['points'][$g][2] = " ";
//							$tr.= "miss";
						}
					}//end of pick 2 check
					
					else if($p==3){
						//THIRD PICK CHECK
						if($users[$uid]['pick'][$g][3] == $leaderboard[$g][3]){
							$pts += 0.5;
							$users[$uid]['status'][$g][3] = "tinybullseye";
							$users[$uid]['points'][$g][3] = "+0.5 pts";
//							$tr.= "bullseye";
						}else{
							$users[$uid]['status'][$g][3] = "totalmiss";
							$users[$uid]['points'][$g][3] = " ";
//							$tr.= "miss";
						}
					}//end of pick 2 check
					
					
//					$tr.= "</br>";
					
				}//end pick for loop
				
//				$tr.= "$pts points </br>";
				$users[$uid]['gpoints'][$g] = $pts;
				$users[$uid]['totalpoints'] += $pts;
				$pts = 0;
				
			}//end group for loop
			
//			$tr.= $users[$uid]['totalpoints'] ." TOTAL</br>";
			
		}//end foreach user
		
		$toreturn['users'] = $users;
		$toreturn['leaderboard'] = $leaderboard;
		$toreturn['teams'] = $teams;
		$toreturn['results'] = $results;
		$toreturn['groups'] = $groups;
		
		return $toreturn;
		
	}
	
	public function displayAllTeamsAndScores(){
		
		$data = $this->getAllTeamsAndScores();
		$groups = $this->getGroupAlphabet();
		
		$leaderboard = $data['leaderboard'];
		$teams = $data['teams'];
		$results = $data['results'];
		
		foreach($leaderboard as $gid=>$v){
			
			$tr.= "Group ".$groups[$gid]." </br>";
			
			$tr.= $v[0] ." - ";
			$tr.= $teams[$v[0]]['name'] ." - ";
			$tr.= $results[$v[0]]['mp'] ." - ";
			$tr.= $results[$v[0]]['w'] ." - ";
			$tr.= $results[$v[0]]['d'] ." - ";
			$tr.= $results[$v[0]]['l'] ." - ";
			$tr.= $results[$v[0]]['gf'] ." - ";
			$tr.= $results[$v[0]]['ga'] ." - ";
			$tr.= $results[$v[0]]['plusminus'] ." - ";
			$tr.= $results[$v[0]]['pts'] ." </br>";
			
			$tr.= $v[1] ." - ";
			$tr.= $teams[$v[1]]['name'] ." - ";
			$tr.= $results[$v[1]]['mp'] ." - ";
			$tr.= $results[$v[1]]['w'] ." - ";
			$tr.= $results[$v[1]]['d'] ." - ";
			$tr.= $results[$v[1]]['l'] ." - ";
			$tr.= $results[$v[1]]['gf'] ." - ";
			$tr.= $results[$v[1]]['ga'] ." - ";
			$tr.= $results[$v[1]]['plusminus'] ." - ";
			$tr.= $results[$v[1]]['pts'] ." </br>";
			
			$tr.= $v[2] ." - ";
			$tr.= $teams[$v[2]]['name'] ." - ";
			$tr.= $results[$v[2]]['mp'] ." - ";
			$tr.= $results[$v[2]]['w'] ." - ";
			$tr.= $results[$v[2]]['d'] ." - ";
			$tr.= $results[$v[2]]['l'] ." - ";
			$tr.= $results[$v[2]]['gf'] ." - ";
			$tr.= $results[$v[2]]['ga'] ." - ";
			$tr.= $results[$v[2]]['plusminus'] ." - ";
			$tr.= $results[$v[2]]['pts'] ." </br>";
			
			$tr.= $v[3] ." - ";
			$tr.= $teams[$v[3]]['name'] ." - ";
			$tr.= $results[$v[3]]['mp'] ." - ";
			$tr.= $results[$v[3]]['w'] ." - ";
			$tr.= $results[$v[3]]['d'] ." - ";
			$tr.= $results[$v[3]]['l'] ." - ";
			$tr.= $results[$v[3]]['gf'] ." - ";
			$tr.= $results[$v[3]]['ga'] ." - ";
			$tr.= $results[$v[3]]['plusminus'] ." - ";
			$tr.= $results[$v[3]]['pts'] ." </br></br>";
			
		}
		
		return $tr;
		
	}
	
	public function getGroupAlphabet(){
		
		
		$group[0] = A;
		$group[1] = B;
		$group[2] = C;
		$group[3] = D;
		$group[4] = E;
		$group[5] = F;
		$group[6] = G;
		$group[7] = H;
		
		return $group;
	}
	
	public function getAllTeamsAndScores(){
		
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->set_charset("utf8")) {$this->errors[] = $this->db_connection->error;}

		if (!$this->db_connection->connect_errno) {
			
			
			$sql = "SELECT * FROM teams";

			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
				$teams[$row['id']]['name'] = $row['team'];
				$teams[$row['id']]['aka'] = $row['aka'];
				$teams[$row['id']]['group'] = $row['group'];
				$teams[$row['id']]['flag'] = $row['flag'];
				$group[$row['group']][] = $row['id'];	
			}//end while SQL fetch
			
			
			$sql = "SELECT * FROM results ORDER BY pts DESC, plusminus DESC";
			
			$result = $this->db_connection->query($sql);
	
			while($row = mysqli_fetch_array($result)){			
				$results[$row['teamid']]['mp'] = $row['mp'];
				$results[$row['teamid']]['w'] = $row['w'];
				$results[$row['teamid']]['d'] = $row['d'];
				$results[$row['teamid']]['l'] = $row['l'];
				$results[$row['teamid']]['gf'] = $row['gf'];
				$results[$row['teamid']]['ga'] = $row['ga'];
				$results[$row['teamid']]['plusminus'] = $row['plusminus'];
				$results[$row['teamid']]['pts'] = $row['pts'];
			}//end while SQL fetch
			
			
		}//end no db connection errors
		
		
		foreach($results as $tid=>$v1){
			$leaderboard[$teams[$tid]['group']][] = $tid;
		}
		
		
		$tr['leaderboard'] = $leaderboard;
		$tr['teams'] = $teams;
		$tr['results'] = $results;
		
		return $tr;
		
	}
	
	public function displayAllUsersAndPicks(){
		
		$users = $this->getAllUsersAndPicks();
		
		foreach($users as $uid=>$v){
			
			$tr.= "--" .strtoupper($users[$uid]['name']) ."--</br></br>";
			
			$tr.= $users[$uid]['pick'][0][0] ."</br>";
			$tr.= $users[$uid]['pick'][0][1] ."</br>";
			$tr.= $users[$uid]['pick'][0][2] ."</br>";
			$tr.= $users[$uid]['pick'][0][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][1][0] ."</br>";
			$tr.= $users[$uid]['pick'][1][1] ."</br>";
			$tr.= $users[$uid]['pick'][1][2] ."</br>";
			$tr.= $users[$uid]['pick'][1][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][2][0] ."</br>";
			$tr.= $users[$uid]['pick'][2][1] ."</br>";
			$tr.= $users[$uid]['pick'][2][2] ."</br>";
			$tr.= $users[$uid]['pick'][2][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][3][0] ."</br>";
			$tr.= $users[$uid]['pick'][3][1] ."</br>";
			$tr.= $users[$uid]['pick'][3][2] ."</br>";
			$tr.= $users[$uid]['pick'][3][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][4][0] ."</br>";
			$tr.= $users[$uid]['pick'][4][1] ."</br>";
			$tr.= $users[$uid]['pick'][4][2] ."</br>";
			$tr.= $users[$uid]['pick'][4][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][5][0] ."</br>";
			$tr.= $users[$uid]['pick'][5][1] ."</br>";
			$tr.= $users[$uid]['pick'][5][2] ."</br>";
			$tr.= $users[$uid]['pick'][5][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][6][0] ."</br>";
			$tr.= $users[$uid]['pick'][6][1] ."</br>";
			$tr.= $users[$uid]['pick'][6][2] ."</br>";
			$tr.= $users[$uid]['pick'][6][3] ."</br></br>";
			
			$tr.= $users[$uid]['pick'][7][0] ."</br>";
			$tr.= $users[$uid]['pick'][7][1] ."</br>";
			$tr.= $users[$uid]['pick'][7][2] ."</br>";
			$tr.= $users[$uid]['pick'][7][3] ."</br></br>";
			
		}
		
		return $tr;
		
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
			}//end while SQL fetch
			
			
			$sql = "SELECT * FROM predictions";
			
			$result = $this->db_connection->query($sql);
			
			while($row = mysqli_fetch_array($result)){
				$users[$row['id']]['picks'] = $row;	
			}//end while SQL fetch
			
			
		}//end no db connection errors
		
		//create clean array with users picks by group and count e.g. [1][1]
		
		foreach($users as $uid=>$v){
			
			unset($v['picks'][0]);
			unset($v['picks']['id']);
			unset($v['picks'][1]);
			unset($v['picks']['uid']); //remove from [picks] unnecesary values like uid
			
			$counter = 0; //counter to reset every 4 for new group
			$group = 0;
			
			for($p=2;$p<34;$p++){
				
				//$tr.= "$group . $counter = ".$v['picks'][$p]."</br>";
				
				$users[$uid]['pick'][$group][$counter] = $v['picks'][$p];
				
				$counter++;
				
				if($counter==4){$group++;$counter=0;}
				
			}//end for (goes through all users picks in order)
			
			$unset[$v]['picks'];//unset picks and leaves ordered pick
			
		}//end foreach $users
		
		
		return $users;
		
		
	}//end function
	
	
}//end class FSEvent
	
?>