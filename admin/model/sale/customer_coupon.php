<?php
class ModelSaleCustomerCoupon extends Model {
	public function addCoupon($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_coupon` SET customer_id = '" . (int)$data['customer_id'] . "', coupon_id = '" . (int)$data['coupon_id'] . "', description = '" . $this->db->escape($data['description']) . "', isused = 0 , date_added = NOW()";
	}

	public function deleteCoupon($customer_coupon_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_coupon` WHERE customer_coupon_id = '" . (int)$customer_coupon_id . "'");
	}
}