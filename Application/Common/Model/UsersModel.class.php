<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14
 * Time: 19:47
 */
namespace Common\Model;
/**
 * Class UsersModel 用户模型
 * @package Common\Model
 */
class UsersModel extends \Common\Helper\Model{
    protected $tableName = 'users';
    /*
     * 用户类型
     */
    const TYPE_MANAGE = 1;//管理员
    const TYPE_PERSONNEL = 2;//工作人员
    const TYPE_TEACHER = 3;//指导老师
    const TYPE_JUDGES = 4;//评审专家
    const TYPE_INVESTMENT = 5;//投资人
    const TYPE_STUDENT = 6;//学生
    
    /*
     * 证件类型
     */
    const CARD_TYPE_STUDENT = 1;
    const CARD_TYPE_ID_CARD = 2;
    
    /*
     * 专业
     */
    const MAJOR_1 = 1;
    const MAJOR_2 = 2;
    const MAJOR_3 = 3;
    const MAJOR_4 = 4;
    const MAJOR_5 = 5;
    const MAJOR_6 = 6;
    const MAJOR_7 = 7;
    const MAJOR_8 = 8;
    const MAJOR_9 = 9;
    const MAJOR_10 = 10;
    const MAJOR_11 = 11;
    const MAJOR_12 = 12;
    const MAJOR_13 = 13;
    const MAJOR_14 = 14;
    const MAJOR_15 = 15;
    const MAJOR_16 = 16;
    const MAJOR_17 = 17;
    const MAJOR_18 = 18;
    const MAJOR_19 = 19;
    const MAJOR_20 = 20;
    const MAJOR_21 = 21;
    const MAJOR_22 = 22;
    const MAJOR_23 = 23;
    const MAJOR_24 = 24;
    const MAJOR_25 = 25;
    const MAJOR_26 = 26;
    const MAJOR_27 = 27;
    const MAJOR_28 = 28;
    const MAJOR_29 = 29;
    const MAJOR_30 = 30;
    const MAJOR_31 = 31;
    const MAJOR_32 = 32;
    const MAJOR_33 = 33;
    const MAJOR_34 = 34;
    
    /*
     * 学历
     */
    const DEGREE_1 = 1;
    const DEGREE_2 = 2;
    const DEGREE_3 = 3;
    const DEGREE_4 = 4;
    /**
     * 学历
     * @var array
     */
    public static  $degree = array(
        self::DEGREE_1 => '本科',
        self::DEGREE_2 => '研究生',
        self::DEGREE_3 => '博士生',
        self::DEGREE_4 => '其他',
    );
    /**
     * 专业
     * @var array
     */
    public static  $major = array(
        self::MAJOR_1 => '经济学院',
        self::MAJOR_2 => '建筑与环境学院',
        self::MAJOR_3 => '法学院',
        self::MAJOR_4 => '化学工程学院',
        self::MAJOR_5 => '文学与新闻学院',
        self::MAJOR_6 => '轻纺与食品学院',
        self::MAJOR_7 => '外国语学院',
        self::MAJOR_8 => '高分子科学与工程学院',
        self::MAJOR_9 => '艺术学院',
        self::MAJOR_10 => '华西基础医学与法医学院',
        self::MAJOR_11 => '历史文化学院(旅游学院)',
        self::MAJOR_12 => '华西临床医学院(华西医院)',
        self::MAJOR_13 => '数学学院',
        self::MAJOR_14 => '华西第二医院',
        self::MAJOR_15 => '物理科学与技术学院（核科学与工程技术学院）',
        self::MAJOR_16 => '华西口腔医学院（华西口腔医院）',
        self::MAJOR_17 => '化学学院',
        self::MAJOR_18 => '华西公共卫生学院(华西第四医院)',
        self::MAJOR_19 => '生命科学学院',
        self::MAJOR_20 => '华西药学院',
        self::MAJOR_21 => '电子信息学院',
        self::MAJOR_22 => '公共管理学院',
        self::MAJOR_23 => '材料科学与工程学院',
        self::MAJOR_24 => '商学院',
        self::MAJOR_25 => '制造科学与工程学院',
        self::MAJOR_26 => '马克思主义学院（政治学院）',
        self::MAJOR_27 => '电气信息学院',
        self::MAJOR_28 => '体育学院',
        self::MAJOR_29 => '计算机学院',
        self::MAJOR_30 => '灾后重建与管理学院',
        self::MAJOR_31 => '软件学院',
        self::MAJOR_32 => '空天科学与工程学院',
        self::MAJOR_33 => '水利水电学院',
        self::MAJOR_34 => '匹兹堡学院',
    );
    /**
     * 证件类型
     * @var array
     */
    public static  $card_type = array(
        self::CARD_TYPE_STUDENT => '学生证',
        self::CARD_TYPE_ID_CARD => '身份证',
    );
    /**
     * 用户类型
     * @var array
     */
    public static  $user_type = array(
        self::TYPE_MANAGE => '管理员',
        self::TYPE_PERSONNEL => '工作人员',
        self::TYPE_TEACHER => '指导老师',
        self::TYPE_JUDGES => '评审专家',
        self::TYPE_INVESTMENT => '投资人',
        self::TYPE_STUDENT => '学生'
    );
    /**
     * 添加用户
     * 添加时间14:57:18
     * 
     * @author yzx
     * @param array $userData
     * @return boolean|Ambigous <mixed, boolean, unknown, string>
     */
    public function addUser($userData) {
        $time = time();
        $studentModel = new StudentsModel();
        $teachersModel = new TeachersModel();
        $user_data = array();
        
        $user_data['user_name'] = $userData['user_name'];
        $user_data['user_type'] = $userData['user_type'];
        $user_data['email'] = $userData['email'];
        $user_data['passwd'] = create_password($userData['passwd']);
        $user_data['sex'] = $userData['sex'];
        $user_data['birth'] = $userData['birth'];
        $user_data['nation'] = $userData['nation'];
        $user_data['card_id'] = $userData['card_id'];
        $user_data['card_type'] = $userData['card_type'];
		$user_data['group_id'] = $userData['group_id'];
		$user_data['state'] = $userData['state'];
        $user_data['create_time'] = $time;
        $user_data['last_time'] = $time;
        $email_data = $this->where(array('email' => $userData['email']))->find();
        if (!empty($email_data)){
            return array('status'=>false,'msg'=>'邮箱已经存在');
        }
		$card_id = $this->where(array('card_id' => $userData['card_id']))->find();
		if (!empty($card_id)){
            return array('status'=>false,'msg'=>'证件号已经存在');
        }
		
        $this->startTrans();
        $user_id = $this->add($user_data);
        if (!$user_id){
            $this->rollback();
            return array('status'=>false,'msg'=>'添加用户信息失败');
        }
        if ($userData['user_type'] == self::TYPE_STUDENT){
            $student_data = array();
            $student_data['stu_card'] = $userData['card_id'];
            $student_data['user_id'] = $user_id;
            $student_data['college'] = $userData['college'];
            $student_data['major'] = $userData['major'];
            $student_data['degree'] = $userData['degree'];
            $student_data['enrollment'] = $time;
            $student_id = $studentModel->add($student_data);
            if (!$student_id){
                $this->rollback();
                return array('status'=>false,'msg'=>'添加用户额外信息失败');
            }
        }
        if ($userData['user_type'] == self::TYPE_TEACHER){
            $teachers_data = array();
            $teachers_data['user_id'] = $user_id;
            $teachers_data['union'] = $userData['college'];
            $teachers_id = $teachersModel->add($teachers_data);
            if (!$teachers_id){
                $this->rollback();
                return array('status'=>false,'msg'=>'添加教师信息失败');
            }
        }
        $this->commit();
        return array('status'=>$user_id,'msg'=>'添加教师信息失败');
    }
}