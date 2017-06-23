<?php

namespace App\Models;

class TestSphinx extends Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=60, nullable=false)
     */
    public $user_login;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=false)
     */
    public $user_pass;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $user_nicename;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $user_email;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    public $user_url;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $avatar;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $sex;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $birthday;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $signature;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    public $last_login_ip;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $last_login_time;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $create_time;

    /**
     *
     * @var string
     * @Column(type="string", length=60, nullable=true)
     */
    public $user_activation_key;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $user_status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $score;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $user_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $coin;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $mobile;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    public $token;

    /**
     *
     * @var integer
     * @Column(type="integer", length=12, nullable=true)
     */
    public $expiretime;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $islive;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $showid;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $recommend;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $weixin;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $experience;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $consumption;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $votes;

    /**
     *
     * @var integer
     * @Column(type="integer", length=20, nullable=true)
     */
    public $votestotal;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $province;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    public $city;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=true)
     */
    public $isrecommend;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $openid;

    /**
     *
     * @var string
     * @Column(type="string", length=20, nullable=true)
     */
    public $login_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $xieyi_version;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $wx_unionid;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $hx_uid;

    /**
     *
     * @var string
     * @Column(type="string", length=40, nullable=false)
     */
    public $hx_pwd;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $last_login_phone;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $last_login_version;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $chips;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $avatar_height;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $avatar_width;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $channel;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $country;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $admin;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $ban_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $pet_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $pet_nest_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $pet_exp;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $pet_level;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $pet_satiation;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $pet_eattime;

    /**
     *
     * @var double
     * @Column(type="double", length=11, nullable=true)
     */
    public $cash_rate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("phalcon");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'test_sphinx';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TestSphinx[]|TestSphinx
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TestSphinx
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
