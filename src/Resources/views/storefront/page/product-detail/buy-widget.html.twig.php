{% sw_extends '@Storefront/storefront/page/product-detail/buy-widget.html.twig' %}

{% block page_product_detail_buy_product_price %}
{{ parent() }}

{% set lowestPrice = page.product.extensions.offerta_lowest_price.value ?? null %}

{% if lowestPrice is not null %}
<div class="product-offerta-lowest-price mt-2 text-muted" style="font-size: 0.9rem;">
    Niedrigster Preis der letzten 30 Tage: {{ lowestPrice|currency }}
</div>
{% endif %}
{% endblock %}
