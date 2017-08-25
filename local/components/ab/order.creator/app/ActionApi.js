/**
 * Created by dremin_s on 17.08.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

const getUrl = (action) => {
	return '/rest/orderCreate/' + action;
};

export default {
	getBasketItems: {method: 'GET', url: getUrl('getBasket')},
	updateQuantity: {method: 'POST', url: getUrl('updateQuantity')},
	deleteBasketItem: {method: 'POST', url: getUrl('deleteItem')},
	getDelivery: {method: 'GET', url: getUrl('getDelivery')},
	searchLocation: {method: 'GET', url: getUrl('searchLocation')},
	saveOrder: {method: 'POST', url: getUrl('saveOrder')},
	getPayments: {method: 'POST', url: getUrl('getPayments')},
	getStreet: {method: 'GET', url: getUrl('getStreet')},
}