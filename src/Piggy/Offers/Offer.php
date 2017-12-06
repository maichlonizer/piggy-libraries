<?php

namespace Piggy\Offers;

use RedBeanPHP\R;

class Offer
{
    public function find( $params = array() )
    {
        
        $query = "SELECT * FROM {p}".self::$offers_table." WHERE advertiser_id = :network_id";
        $query_params = array(':network_id'=> $this->network_id );


        if ( isset($params['offer_name']) ){
            $query=$query." AND offer_name = :offer_name";
            $query_params[':offer_name'] = $params['offer_name'];
        }
        
        if ( isset($params['offer_url']) ){
            $query=$query." AND offer_url = :offer_url";
            $query_params[':offer_url'] = $params['offer_url'];
        }
        
        if ( isset($params['network_offer_id']) ){
            $query=$query." AND network_offer_id = :network_offer_id";
            $query_params[':network_offer_id'] = $params['network_offer_id'];
        }
        
        $rows = R::getAll($this->db->prepareQuery($query), $query_params);
        
        return $rows;
    }
}
