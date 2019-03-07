<?php

class GS {

    public $plugin_version = "4.0.4";
    private $gs_url = "https://api.at.getsocial.io";
    private $gs_account = "https://getsocial.io";
    private $gs_url_api = "//api.at.getsocial.io";
    public $api_url = "https://getsocial.io/api/v1/";

    function __construct($api_key, $identifier, $lang) {
        $this->api_key = $api_key;
        $this->identifier = $identifier;
        $this->lang = $lang == null ? 'en' : $lang;
    }

    private function api($path) {
        try {
            $r = wp_remote_get($this->api_url.$path, array( 'sslverify' => false ));

            if (is_wp_error($r)):
                return null;
            endif;

            if ($r['response']['code'] == 200) {
                return json_decode($r['body']);
            } else {
                return null;
            }
        } catch (HttpException $ex) {
            echo "Error: ".$ex;
        }
    }

    function utms($app) {
        return '&amp;utm_source=wordpress-user&amp;utm_medium=plugin&amp;utm_term='.get_option('siteurl').'&amp;utm_content='.$app.'&amp;utm_campaign=Wordpress%20Plugin';
    }

    function api_url($path) {
        return $this->api_url.$path;
    }

    function getSite() {
        if ($this->api_key != ''):
            return $this->api('sites/'.$this->api_key);
        else:
            return null;
        endif;
    }

    function refreshSite($data = null) {
        if ($data == null) {
            $site = (array) $this->getSite();
        } else {
            $site = $data;
        }

        if($site != null):
            $this->save($site);
        endif;
    }

    function save($site_info) {
        update_option('gs-identifier', $site_info['identifier']);
        update_option('gs-pro', $site_info['pro']);
        update_option('gs-user-email', $site_info['user_email']);
        update_option('gs-apps', json_encode($site_info['gs_apps']));
        update_option('gs-ask-review', $site_info['ask_review']);
        update_option('gs-has-subscriptions', $site_info['has_subscriptions']);
        update_option('gs-alert-msg', $site_info['alert_msg']);
        update_option('gs-alert-utm', $site_info['alert_utm']);
        update_option('gs-alert-cta', $site_info['alert_cta']);
    }

    function apps($app_name) {
        $apps = json_decode(get_option('gs-apps'), true);

        if ($apps == null) {
            return false;
        } else {
            return (array_key_exists($app_name, $apps) ? $apps[$app_name] : false);
        }
    }

    function is_pro() {
        return get_option('gs-pro');
    }

    function has_subscriptions() {
        return get_option('gs-has-subscriptions');
    }

    function is_active($app_name) {
        $app = $this->apps($app_name);

        return (!empty($app) ? $app['active'] == 'true' : false);
    }

    function prop($app_name, $prop) {
        $app = $this->apps($app_name);

        return $app[$prop];
    }

    function getLib() {
        $code = <<<EOF
<script type="text/javascript">
    "function"!=typeof loadGsLib&&(loadGsLib=function(){var e=document.createElement("script");
    e.type="text/javascript",e.async=!0,e.src="$this->gs_url_api/widget/v1/gs_async.js?id=$this->identifier";
    var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)})();
    var GETSOCIAL_VERSION = "$this->plugin_version";
</script>
EOF;
        return $code;
    }



    function getCode($app_name, $post_url = null, $post_title = null, $post_image = null, $price = null, $currency = null, $add_custom_tags = false) {

        $gs_custom_tags = '';

        if ($add_custom_tags) {
            $gs_custom_tags .= 'data-url="' . $post_url . '" ';
            $gs_custom_tags .= 'data-title="' . $post_title . '" ';
            $gs_custom_tags .= $post_image != null ? 'data-image="' . $post_image . '"' : '';
        }

        switch ($app_name) {
            case 'sharing_bar':
                return '<div class="getsocial gs-inline-group" ' . $gs_custom_tags . '></div>';
            case 'native_bar':
                return '<div class="getsocial gs-native-bar" ' . $gs_custom_tags . '></div>';
            case 'custom_actions':
                return '<div class="getsocial gs-custom-actions" ' . $gs_custom_tags . '></div>';
            case 'social_bar_big_counter':
                return '<div class="getsocial gs-inline-group gs-big-counter" ' . $gs_custom_tags . '></div>';
            case 'follow_bar':
                return '<div class="getsocial gs-inline-group gs-follow" ' . $gs_custom_tags . '></div>';
            case 'price_alert':
                return '<div class="getsocial gs-price-alert" ' . $gs_custom_tags . ' data-price="' . $price . '" data-currency="' . $currency . '"></div>';
            case 'reaction_buttons':
                return '<div class="getsocial gs-reaction-button"' . $gs_custom_tags . '></div>';
            default:
                return '';
        }
    }

    function gs_account(){
        return $this->gs_account;
    }
}
