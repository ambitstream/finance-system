<?php

class FlowController extends Controller {
	#protected $flow;
	function beforeroute(
		
	) {
		$this->f3->set('message','');
		//необходимо для вывода в таблице расходов без view (шаблона)
		
		$currencies = new Currency($this->db);
		$this->f3->set('currencies',$currencies);
		$categories = new Category($this->db);
		$this->f3->set('categories',$categories);
		$wallets = new Wallet($this->db);
		$this->f3->set('wallets',$wallets);
		$periods = new Period($this->db);
		$this->f3->set('periods',$periods);
	}
	
	public function index()
    {
        $flow = new Flow($this->db);
        $this->f3->set('flows',$flow->all());
        $this->f3->set('page_head','Spending List');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('view','flow/list.htm');
	}
	
	public function indexpaged()
    {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
        $flow = new Flow($this->db);
		
		$this->f3->set('include', $this->f3->exists('GET.include') ? explode(",", $this->f3->get('GET.include')) : false );
		$flows = $flow->allpaged($this->f3->get('GET.page'),$this->f3->get('GET.limit'),$this->f3->get('include'));
		for ($i=0; $i < $flows["count"]; $i++) { 
			$pages[] = array(
				"link" => "/?page=".$i."&limit=40&include=".$this->f3->get('GET.include'),
				"count" => $i+1,
				"active" => ( $this->f3->exists('GET.page') && $this->f3->get('GET.page') == $i ) ? 1 : 0
			);
		}
		$this->f3->set('pages',$pages);		
		$this->f3->set('flows', $flows);
		$this->f3->set('list',$flows);
        $this->f3->set('page_head','Spending List');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));		
        $this->f3->set('view','flow/list_paged.htm');
	}	
	
	//авторизация	
	function login() { 
		$this->f3->set('page_head','Login panel');
		$mapper = new DB\SQL\Mapper($this->db, 'account');
		$auth = new Auth($mapper, array('id'=>'username','pw'=>'password'));
		if($this->f3->exists('POST.autorize')) {
			$login = $this->f3->get('POST.login');
			$password = $this->f3->get('POST.password');
			$login_result = $auth->login($login,$password);
			if($login_result==1){
			

				$this->f3->set('SESSION.is_admin',$login_result);
				$this->f3->reroute("/");
				exit;
			}else{
				$this->f3->set('message', 'Ошибка авторизации!');
			}
		} 
		$this->f3->set('view','flow/login.htm');
	}
	
	public function statistic()
    {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
		$this->f3->set('page_head','Super test');
		
		
		
		
		$periods = $this->db->exec('
			SELECT * FROM period;
		');

		$this->f3->set('periods',array());
		$this->f3->set('periods_average',array());
		
		$this->f3->set('periods_monthly_average',array());
		$this->f3->set('periods_total_average',array());
		
		$i=0;
		foreach ($periods as $period) {
			$i++;	
			
			//count duration: number of days in period
			$period_start = date_create( $period['period_start']);
			$period_end   = date_create( $period['period_end']);
			
			$period_duration = date_diff( $period_start, $period_end );
			$period_duration = $period_duration->days+1;
			
			// select everything from period and divide sum on the duration
			$period_average = $this->db->exec('
				SELECT a.id as category_id, SUM(b.sum) / '.$period_duration.' as `category_average`
			FROM category as a
			LEFT OUTER JOIN flow as b
			ON (a.id = b.category_id AND b.type = 0 AND  b.period = '.$i.' 
			AND b.ignore_in_reports != 1)
			WHERE a.type = "out"
			GROUP BY a.id
			ORDER BY a.id;

			');
			
			
			// select everything from period  and divide sum on the duration  - WITHOUT yearly-reporting categories
			$period_monthly_average = $this->db->exec('
				SELECT SUM(a.sum) / '.$period_duration.' as value
										FROM  flow a 
										Join category b
										ON (a.category_id = b.id)
										WHERE a.type = 0 AND  a.period = '.$i.' AND b.reporting_type != "yearly" 
										AND a.ignore_in_reports != 1;
			');
						
			// select everything from period and divide sum on the duration
			$period_total_average = $this->db->exec('
			SELECT SUM(sum) / '.$period_duration.' as value
						FROM  flow 
						WHERE type = 0 AND  period = '.$i.'
						AND ignore_in_reports != 1;
			');
			
			
			
			$this->f3->push('periods', $period);
			$this->f3->push('periods_average', $period_average);
			
			$this->f3->push('periods_monthly_average', $period_monthly_average);
			$this->f3->push('periods_total_average', $period_total_average);

			// echo '<pre>';
			// print_r ($period_total_average);
			// echo '</pre>';
		}
		
		
		//per week
		$this->f3->set('chartPerWeek',$this->db->exec('
			SELECT a.id as cat_id, SUM(b.sum) as `sum`, yearweek(date,1) as `week`
						FROM category as a
						JOIN flow as b
						ON (a.id = b.category_id AND b.type = 0 AND  b.period = '.$i.')
						WHERE a.type = "out" AND a.reporting_type="weekly"
						GROUP BY `week`,  a.id
						ORDER BY a.id, `week`
						AND b.ignore_in_reports != 1;	
		'));

		
		
		
		$this->f3->set('result',$this->db->exec('
			select a.`category_id`, b.`name`,
			sum(`sum`) as `summa` 
			from `flow` a, `category` b 
			where a.`category_id` = b.`id` and a.`type`=0
			AND category_id!=14 AND category_id!=15
			AND MONTH(a.`date`) = MONTH(CURDATE())
			AND YEAR(a.`date`) = YEAR(CURDATE())
			GROUP BY `category_id`
			ORDER BY  `summa` DESC;
		'));
		
		// sum in/out per month - in chart
		$this->f3->set('flowpermonth',$this->db->exec('			
			SELECT * FROM
			(select YEAR(`date`) as year, MONTH(`date`) as mnth, date, `type`, sum(`sum`) as `sum_out` 
			from `flow` a 
			WHERE `type`=0
			GROUP BY YEAR(`date`), MONTH(`date`)) a
			right OUTER JOIN
			(select YEAR(`date`) as year2, MONTH(`date`) as mnth2,`date`, `type`, sum(`sum`) as `sum_in` 
			from `flow` a 
			WHERE `type`=1
			GROUP BY YEAR(`date`), MONTH(`date`), `type`) b
			ON a.`mnth` = b.`mnth2` AND a.`year` = b.`year2`
			ORDER BY a.date;		
		'));
		// sum out per day - in chart
		$this->f3->set('outcomeperday',$this->db->exec('			
			select DAY(`date`) as `date`, sum(`sum`) as `sum`
						from `flow`
						WHERE (`type`=0) 
						AND (
							MONTH(`date`) = MONTH(CURDATE())
							OR MONTH(`date`) = MONTH(CURDATE())-1
						)
						AND YEAR(`date`) = YEAR(CURDATE())
						GROUP BY `date`;
		'));
		
		// average in/out per month 
		$countmonth = $this->db->exec('
			SELECT TIMESTAMPDIFF(MONTH,MIN(`date`),MAX(`date`)) as period
			FROM flow;
		');	
		$this->f3->set('averoutpermonth',$this->db->exec('		
			select SUM(`sum`) / (1+'.$countmonth[0]['period'].') as `averbymonth`
			from `flow`
			WHERE `type` = 0;
		'));
		$this->f3->set('averinpermonth',$this->db->exec('		
			select SUM(`sum`) / (1+'.$countmonth[0]['period'].') as `averbymonth`
			from `flow`
			WHERE `type` = 1;
		'));
		
		

		$this->f3->set('total_out',$this->db->exec('		
			select SUM(`sum`) as `total_out`
			from `flow`
			WHERE `type` = 0;
		'));
		$this->f3->set('total_in',$this->db->exec('		
			select SUM(`sum`) as `total_in`
			from `flow`
			WHERE `type` = 1;
		'));
		
		
        $this->f3->set('view','flow/stat.htm');
	}
	
	public function calendar()
    {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
		$this->f3->set('page_head','Super test');
		// $this->f3->set('include', $this->f3->exists('GET.include') ? explode(",", $this->f3->get('GET.include')) : false );

		// $checked_categories = $this->f3->exists('GET.include') && $this->f3->get('GET.include') != '' ? $this->f3->get('GET.include') : false;
		$this->f3->set('flowperday',$this->db->exec('
			SELECT date,SUM(sum) as `summa`, SUM(currency_sum) as `cur_summa`, currency_id
			FROM flow 
			WHERE type=0 
			GROUP BY date, currency_id
			ORDER BY date DESC;
		'));

        $this->f3->set('view','flow/calendar.htm');
		

	}

    public function create()
    {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
		$this->f3->set('timestamp',date("Y-m-d"));
				
        if($this->f3->exists('POST.create')) {
			$rate = $this->db->exec('
				select *
				from `currency`
				where `id` = '.$this->f3->get('POST.currency_id').';
			');
			$usd_sum=($this->f3->get('POST.sum')/$rate[0]['selling_rate']);
			$this->f3->set('POST.currency_sum', $this->f3->get('POST.sum'));
			$this->f3->set('POST.sum', $usd_sum);
            $flow = new Flow($this->db);
            $flow->add();
            $this->f3->reroute('/success/outcomming_added');
        } else {
            $this->f3->set('page_head','Добавить расход');
            $this->f3->set('view','flow/create.htm');
        }

    }

    public function update() {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
        $flow = new Flow($this->db);

        if($this->f3->exists('POST.update')) {
			$rate = $this->db->exec('
				select *
				from `currency`
				where `id` = '.$this->f3->get('POST.currency_id').';
			');
			$usd_sum=($this->f3->get('POST.sum')/$rate[0]['selling_rate']);
			$this->f3->set('POST.currency_sum', $this->f3->get('POST.sum'));
			$this->f3->set('POST.sum', $usd_sum);
            $flow->edit($this->f3->get('POST.id'));
            $this->f3->reroute('/success/outcomming_updated');
        } else {
            $flow->getById($this->f3->get('PARAMS.id'));
            $this->f3->set('flow',$flow);
            $this->f3->set('page_head','Изменить запись');
            $this->f3->set('view','flow/update.htm');
        }

    }

    public function delete() {
		if(!$this->f3->exists('SESSION.is_admin')){
			$this->f3->reroute("/login");
			exit;
		}
        if($this->f3->exists('PARAMS.id'))
        {
            $flow = new Flow($this->db);
            $flow->delete($this->f3->get('PARAMS.id'));
        }
		$this->f3->set('page_head','Запись удалена');
        $this->f3->reroute('/success/outcomming_deleted');
    }
}