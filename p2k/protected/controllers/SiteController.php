<?php

class SiteController extends Controller
{
	private $aUrls = array(
		//array('label'=>'Home', 'url'=>'/', 'active'=>false),
		array('label'=>'Алкорейтинг', 'url'=>'/site/table', 'active'=>false),
		array('label'=>'О нас', 'url'=>'/site/about', 'active'=>false),
		
	);
	
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		
		return array(
			
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),

			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * Основной индекс
	 * 
	 */
	public function actionIndex(){
		

		if( Yii::app()->user->isGuest ){
			$sUserName = '%Username%';
		} else {
			$sUserName = Yii::app()->user->name;
			$iUserId = UserIdentity::getUserId( $sUserName );

		}

		$oMatchList = new CActiveDataProvider(
			'Match',
			array(
				'pagination' => array(
					'pageSize' => 30,
				),
				'criteria'=>array(
					'condition'=>'id<25',
				)
			)
		);
		
		// матчи ПО
		$oMatchPOList = new CActiveDataProvider(
			'Match',
			array(
				'pagination' => array(
					'pageSize' => 30,
				),
				'criteria'=>array(
					'condition'=>'id>24',
				)
			)
		);
		
		$this->render('index', array(
			'sUserName' => $sUserName,
			'oMatchList'	=> $oMatchList,
			'aSt'			=> array(),
			'oMatchPOList'	=> $oMatchPOList
		));
	}

	/**
	 * Страница about
	 */
	public function actionAbout(){
		
		$oUser = new User();
		$oUser->setAttributes(array(
			'name' => 'Pavel'
		));
		//$oUser->save();
		
		$this->render('about');
	}
	
	/**
	 * Таблица статистики
	 */
	public function actionTable(){

		$this->render(
			'table', 
			array()
		);
	}
		
	/**
	 * Верхняя менюшка
	 * 
	 * @return array 
	 */
	public function getMenu(){
		
		foreach( $this->aUrls as $key => $aItem ) {
			if( $aItem['url'] == '/'.Yii::app()->request->getPathInfo()  ) {
				$this->aUrls[$key]['active'] = true;
			}
		}
		
		$aMenu = array(
			array(
				'class'=>'bootstrap.widgets.BootMenu',
				'items'=> $this->aUrls
			),
		);
		
		if( Yii::app()->user->isGuest ){
			$aMenu[] = array(
				'class'=>'bootstrap.widgets.BootMenu',
				'htmlOptions'=>array('class'=>'pull-right'),
				'items'=>array(
					array('label'=>'Login', 'url'=>'/site/login'),
				),
			);

		} else {
			$aMenu[] = array(
				'class'=>'bootstrap.widgets.BootMenu',
				'htmlOptions'=>array('class'=>'pull-right'),
				'items'=>array(
					array('label'=>'Logout', 'url'=>'/site/logout'),
				),
			);
		}
		
		return $aMenu;
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin() {
		
		if( !Yii::app()->user->isGuest ){
			$this->redirect('/');
		}
		
		$model = new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm'])) {
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect('/');
		}
		// display the login form
		$this->render( 'login', array( 'model' =>$model ) );
	}

	/**
	 * Разлогирование
	 */
	public function actionLogout() {
		
		Yii::app()->user->logout();
		
		$this->redirect('/');
	}
	
	/**
	 * Аяксовая голосовалка
	 */
	public function actionBet(){
		
		if( !Yii::app()->user->isGuest ){
			
			$iMatchId = Yii::app()->request->getParam('match_id');
			$bBet = Yii::app()->request->getParam('bet');
		
			$iUserId = Yii::app()->user->getId();
		
			$oMatch = Match::model()->findByPk( $iMatchId );
			// время не ислекло
			if(strtotime( $oMatch->date ) >= date(mktime()) ){
				// проставить
				$oUSerMatch = UserMatch::model()->findByAttributes(array( 'match_id' => $iMatchId ));
				if( $oUSerMatch ){
					$oUSerMatch->bet = $bBet;
					$oUSerMatch->save();
				}
				echo '<span class="label label-info">'.Yii::app()->user->getName().'</span>';
			}
		}
		
		echo '';
	}
}