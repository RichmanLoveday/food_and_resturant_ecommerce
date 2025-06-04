<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $delivery_area_id
 * @property string|null $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DeliveryArea $deliveryArea
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDeliveryAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $status
 * @property int $show_at_home
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereShowAtHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $quantity
 * @property int $min_purchase_amount
 * @property string $expire_date
 * @property string $discount_type
 * @property string $discount
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CouponFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMinPurchaseAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 */
	class Coupon extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $area_name
 * @property string $min_delivery_time
 * @property string $max_delivery_time
 * @property float $delivery_fee
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $userAddress
 * @property-read int|null $user_address_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereMaxDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereMinDeliveryTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryArea whereUpdatedAt($value)
 */
	class DeliveryArea extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $invoice_id
 * @property string|null $transaction_id
 * @property int $user_id
 * @property string $address
 * @property float $discount
 * @property float $delivery_charge
 * @property float $subtotal
 * @property float $grand_total
 * @property int $product_qty
 * @property string|null $payment_method
 * @property string $payment_status
 * @property string|null $payment_approve_date
 * @property string|null $coupon_info
 * @property string|null $currency_name
 * @property string $order_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCouponInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCurrencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentApproveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProductQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $prooduct_id
 * @property string $product_name
 * @property float $unit_price
 * @property int $qty
 * @property string|null $product_size
 * @property string|null $product_option
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProoductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentGatewaySetting whereValue($value)
 */
	class PaymentGatewaySetting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $thumb_image
 * @property string $short_description
 * @property string $long_description
 * @property float $price
 * @property float $offer_price
 * @property int $quantity
 * @property string|null $sku
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property int $show_at_home
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductGallery> $productImages
 * @property-read int|null $product_images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductOption> $productOptions
 * @property-read int|null $product_options_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductSize> $productSizes
 * @property-read int|null $product_sizes_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOfferPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShowAtHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereThumbImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductGallery whereUpdatedAt($value)
 */
	class ProductGallery extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductOption whereUpdatedAt($value)
 */
	class ProductOption extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSize whereUpdatedAt($value)
 */
	class ProductSize extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SectionTitle whereValue($value)
 */
	class SectionTitle extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $image
 * @property string|null $offer
 * @property string $title
 * @property string $sub_title
 * @property string $short_description
 * @property int $status
 * @property string|null $button_link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SliderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereButtonLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Slider whereUpdatedAt($value)
 */
	class Slider extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $avatar
 * @property string $name
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $short_description
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\WhyChooseUsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhyChooseUs whereUpdatedAt($value)
 */
	class WhyChooseUs extends \Eloquent {}
}

