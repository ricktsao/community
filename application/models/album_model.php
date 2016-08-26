<?php
Class Album_model extends IT_Model
{
	private $path;


	function __construct() 
	{
		parent::__construct();

		$this->path =  base_url()."upload/website/album/";

	}

	public function GetAlbumList( $condition = NULL , $rows = NULL , $page = NULL , $sort = array() )
	{
		$sql = "	SELECT 	SQL_CALC_FOUND_ROWS
							album.*,album_category.title as category_title
					FROM 	album
					LEFT JOIN album_category on album.album_category_sn = album_category.sn
					WHERE ( 1 )
					";

		if( $condition != NULL )
		{
			$sql .= " AND ( ".$condition." ) ";
		}

		$sql .= $this->getSortSQL( $sort );
			
		$sql .= $this->getLimitSQL( $rows , $page );

		$data = array
		(
			"sql" => $sql ,
			"data" => $this->readQuery( $sql ) ,
			"count" => $this->getRowsCount()
		);		

		return $data;
	}

	public function GetHomeAlbumList(){
		//$path = base_url()."upload/website/album/";
		$sql = "SELECT title,sn,start_date FROM album  ORDER BY start_date DESC  LIMIT 0,3";
		$data =  $this->readQuery( $sql );
		
		for($i=0;$i<count($data);$i++){

			$itemSql = "SELECT 
			CONCAT('".$this->path ."',img_filename) as img_filename,
			title FROM album_item WHERE album_sn =".$data[$i]['sn']." and img_filename <>''  ORDER BY sort DESC   LIMIT 0,3";

			$item_result = $this->readQuery( $itemSql );

			$data[$i]['imgs']=$item_result;

		}

	 	return $data;
	}

	public function GetHomeAlbumList2(){
		//$path = base_url()."upload/website/album/";

		$subQuery = "SELECT album_sn,
						CONCAT('{$this->path}',img_filename) as img_filename
					 FROM album_item 
					 WHERE img_filename <>'' AND is_del=0 
					 GROUP BY album_sn
					 ORDER BY sort DESC";

		$sql = "SELECT 
				album.title,
				album.sn,
				album.start_date,
				ai.img_filename
				FROM album INNER JOIN ({$subQuery}) AS ai ON album.sn = ai.album_sn
				WHERE album.is_del = 0
				ORDER BY album.start_date DESC  LIMIT 0,5";

		

		$data =  $this->readQuery( $sql );

		//dprint($data);
		/*
		for($i=0;$i<count($data);$i++){

			$itemSql = "SELECT 
			CONCAT('".$this->path ."',img_filename) as img_filename,
			title FROM album_item WHERE album_sn =".$data[$i]['sn']." and img_filename <>''  ORDER BY sort DESC   LIMIT 0,3";

			$item_result = $this->readQuery( $itemSql );

			$data[$i]['imgs']=$item_result;

		}
*/
	 	return $data;
	}



	public function GetPhoto($sn){
		$itemSql = "SELECT 
		CONCAT('".$this->path ."',img_filename) as img_filename,
		title FROM album_item WHERE album_sn =".$sn." and img_filename <>'' and is_del=0  ORDER BY sort DESC";

		$item_result = $this->readQuery( $itemSql );

		return $item_result;
	}


	public function all_sync(){

		$query = "SELECT * FROM album WHERE is_sync=0";
		$re = $this->runSql($query);
		$data = $re['data'];
		for($i=0;$i<$re['count'];$i++){
			unset($data[$i]['is_sync']);
			$sync_result = $this->sync_to_server($data[$i],"sync_album/updateContent");
			$this->updateData( "album" , array("is_sync"=>$sync_result), "sn =".$data[$i]["sn"]);
		}

		$query = "SELECT * FROM album_item WHERE is_sync=0";
		$re = $this->runSql($query);
		$data = $re['data'];
		for($i=0;$i<$re['count'];$i++){
			unset($data[$i]['is_sync']);
			$sync_result = $this->sync_to_server($data[$i],"sync_album/updatePhoto");
			$this->updateData( "album_item" , array("is_sync"=>$sync_result), "sn =".$data[$i]["sn"]);
		}


	}

	public	function sync_to_server($post_data =null,$page_name){
		//$url = "http://localhost/commapi/sync/updateContent";
		$url = $this->config->item("api_server_url").$page_name;
		//$url = "http://localhost:8080/commapi/".$page_name;
		
		$post_data['comm_id'] =  $this->session->userdata("comm_id");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$is_sync = curl_exec($ch);
		curl_close ($ch);
		
		//更新同步狀況
		//------------------------------------------------------------------------------
		if($is_sync != '1')
		{
			$is_sync = '0';
		}			
		
		return $is_sync;
		//------------------------------------------------------------------------------
	}
	
	
}
