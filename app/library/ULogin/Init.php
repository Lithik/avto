<?php
namespace ULogin;

use \Phalcon\Http\Request;
use \Phalcon\Mvc\Router;
use \Phalcon\Mvc\View\Simple as View;


// use Phalcon\Flash;
// use Phalcon\Session;



/**
 * ULogin init class
 *
 * @package   ULogin
 * @since     PHP >=5.4.28
 * @version   1.0
 * @author    Stanislav WEB | Lugansk <stanisov@gmail.com>
 * @copyright Stanislav WEB
 */
class Init
{

    /**
     * Got user data
     *
     * @var boolean|array
     */
    protected $user = false;

    /**
     * Token key
     *
     * @var boolean
     */
    protected $token = false;

    /**
     * Available auth providers. Default show on the panel
     *
     * @var string
     */
    private $requiredProviders = 'facebook,twitter,odnoklassniki,mailru';

    /**
     * Hidden auth providers. Default hide on the drop down
     *
     * @var string
     */
    private $hiddenProviders = '';

    /**
     * Required providers fields.
     *
     * @var string
     */
    private $requiredFields = 'first_name,last_name,photo';

    /**
     * Optional (additional) fields providers fields.
     *
     * @var string
     */
    private $optionalFields = 'email,nickname,bdate,sex,photo_big,city,country';

    /**
     * Widget types
     *
     * @var array
     */
    protected $types = [
        'small',
        'panel',
        'window'
    ];

    /**
     * Widget. 'small' as default
     *
     * @var string
     */
    private $widget = 'small';

    /**
     * Redirect url
     *
     * @var boolean|string
     */
    private $url = false;

    /**
     * Constructor. Allows you to specify the initial settings for the widget.
     * Parameters can be passed as an associative array.
     * Also, the parameters can be set using the appropriate methods
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (empty($params) === false) {

            foreach ($params as $key => $values) {

                if (method_exists($this, 'set' . ucfirst($key)) === true) {
                    $this->{'set' . ucfirst($key)}($values);
                }
            }

        }
    }

    /**
     * Allows you to add authentication providers in the list of available.
     *
     * @param mixed $providers as ('provider' => true, 'provider' => false) or string separated by comma
     * @example <code>
     *                         $this->setProviders([
     *                         'vkontakte'     =>  true,
     *                         'odnoklassniki' =>  true,
     *                         'mailru'        =>  true,
     *                         'facebook'      =>  true,
     *                         'twitter'       =>  false,  // in drop down
     *                         'google'        =>  false,  // in drop down
     *                         'yandex'        =>  false,  // in drop down
     *                         'livejournal'   =>  false,  // in drop down
     *                         'openid'        =>  false   // in drop down
     *                         ]);
     *
     *          $this->setProviders('vkontakte=true,odnoklassniki=true,mailru=true,openid=false');
     *          </code>
     * @return Init
     */
    public function setProviders($providers)
    {
        $array = Parser::map($providers);

        // collection data
        if (empty($array['required']) === false) {
            $this->requiredProviders = implode(',', $array['required']);
        }

        if (empty($array['hidden']) === false) {
            $this->hiddenProviders = implode(',', $array['hidden']);
        }

        return $this;
    }

    /**
     * Allows you to add to the list of fields requested for the provider's authorization.
     *
     * @param mixed $fields as ('field1', 'field2', ...) or string separated by comma
     * @example <code>
     *                      $this->setFields([
     *                      'first_name',
     *                      'last_name',
     *                      'photo'
     *                      ]);
     *
     *          $this->setFields('first_name,last_name,photo');
     *          </code>
     * @return Init
     */
    public function setFields($fields)
    {

        if (empty($fields) === false) {

            if (is_array($fields) === true) {
                $this->requiredFields = implode(',', $fields);

            } else {
                $this->requiredFields = $fields;


            }
        }

        return $this;
    }

    /**
     * Allows you to add to the list of optionals fields.
     *
     * @param mixed $fields as ('field1', 'field2', ...) or string separated by comma
     * @example <code>
     *                      $this->setOptional([
     *                      'bday',
     *                      'city',
     *                      'sex'
     *                      ]);
     *
     *          $this->setOptional('bday,city,sex');
     *          </code>
     * @return Init
     */
    public function setOptional($fields)
    {

        if (empty($fields) === false) {

            if (is_array($fields) === true) {
                $this->optionalFields = implode(',', $fields);
            } else {
                $this->optionalFields = $fields;

            }
        }

        return $this;

    }

    /**
     * Lets you specify the widget type. Must match the variable `types`
     *
     * @param $type
     * @example <code>
     *          $this->setType('small');
     *          </code>
     * @return Init
     */
    public function setType($type)
    {
        if(is_array($type) === true) {
            $type    = $type[key($type)];
        }

        $this->types = array_flip($this->types);
        if (isset($this->types[$type]) === true) {

            $this->widget = $type;

        }

        return $this;
    }

    /**
     * Lets you specify the callback url to redirect to when authorizing the page is reloaded.
     * If the url is not specified and is used to redirect the authorization,
     * the authorization after the current page just updated
     *
     * @param string $url page that will be implemented to redirect after login (accept QUERY_STRING)
     * @return $this
     */
    public function setUrl($url = '')
    {
        if(is_array($url) === true) {
            $url    = $url[key($url)];
        }

        $request = new Request();

        if (empty($url) === true) {

            $this->url = $request->getScheme() . '://' . $request->getHttpHost() . (new Router())->getRewriteUri();
        } else {
            $this->url = $request->getScheme() . '://' . $request->getHttpHost() . $url;
        }
        return $this;
    }

    /**
     * Destroy user data
     *
     * @return bool
     */
    private function destroyUserData()
    {

        if (is_array($this->user) === true
            && isset($this->user["error"]) === true
        ) {
            $this->user = false;
            return true;
        }
        return false;
    }

    /**
     * Reads the parameters passed to the script, and selects the authorization key ULogin
     *
     * @return bool|mixed
     */
    public function getToken()
    {

        $request = new Request();

        if ($request->isPost() === true) {
            $this->token = $request->getPost('token', null, false);
            // $this->session->set('auth','Гость из социальной сети');//авотризация в сессии

        } else {
            $this->token = $request->getQuery('token', null, false);
        }

        return $this->token;


    }

    /**
     * Returns an associative array with the data about the user.
     * Fields array described in the method setFields
     *
     * @example <code>
     *          $this->getUser();
     *          </code>
     *
     * @return array|bool|mixed data provided by the ISP login
     */
    public function getUser()
    {

        // destroy previous content
        $this->destroyUserData();

        if ($this->user === false) {

            // get user

            $url = 'http://ulogin.ru/token.php?token=' . $this->getToken() . '&host=' . (new Request())->getHttpHost();
            $content = file_get_contents($url);
            $this->user = json_decode($content, true);

            // if use has error , destroy user data
            if ($this->destroyUserData() === true) {
                $this->logout();
            }
        }

        return $this->user;
    }

    /**
     * Checks whether logon
     *
     * @return array|bool|mixed
     */
    public function isAuthorised()
    {

        if (is_array($this->user) === true
            && isset($this->user['error']) === false
        ) {

            return true;
        }

        return $this->getUser();
    }

    /**
     * Allows the user to exit from the system
     *
     * @return null
     */
    protected function logout()
    {

        $this->token = false;
        $this->user = false;

        return null;

    }

    /**
     * Returns the html-form widget
     *
     * @return View
     */
    public function getForm()
    {

        $view = new View();
        $this->getUid();
        return $view->render(__DIR__ . '/../views/ulogin', [
            'widget' => $this->widget,
            'fields' => $this->requiredFields,
            'optional' => $this->optionalFields,
            'providers' => $this->requiredProviders,
            'hidden' => $this->hiddenProviders,
            'url' => $this->url
        ]);

    }
    public function getUid(){  //дописал
        $info = $this->getUser();
        // return $info['email'];
        $uid = $info['uid'];
        // return $email;

        if ($uid==true) {
            $user = User::findFirst(
                    
                    [
                        "conditions" => [
                            "uid" => $uid,
                            "active" => "1"                            
                        ]
                    ]              
                );

            $user_active = User::findFirst(
                    
                    [
                        "conditions" => [
                            "uid" => $uid, 
                            "active" => "0"                            
                        ]
                    ]              
                );
            // $user_new = new User();
            // $user_new->email = "777";

            // $user->email = 123;
                if ($user != false) {
                    $this->_registerSession($user); 
                    header('Location: /store/');
                    
                    
                }
                else if ($user != true && $user_active != true)
                    {
         
                    $this->newUser($info);

                    }

             
        }
         // $this->newUser();
            // $user_new = new User();
            // $user_new->email = "777";
            


    }

    private function _registerSession(User $user)
    {
        // $this->session->remove('email');
        // $this->session->remove('uid');
        $this->session->set('auth', $user->_id);
        $this->session->set('role_id', $user->role_id);
        $this->session->set('email', $user->email);
        $this->session->set('uid', $user->uid);
        $this->session->set('network', $user->social);
        $this->session->set('first_name', $user->first_name);
        $this->session->set('last_name', $user->last_name);

    }
    
    private function newUser($info)
    {
        $user = new User();
        $user->role_id = "2";
        $user->active = "0";
        $user->uid = $info['uid'];
        // $user->email = $info['network'];        
        $user->social = $info['network'];
        $user->first_name = $info['first_name'];
        $user->last_name = $info['last_name'];
        $user->save();
        $this->_registerSession($user);
    }
}
