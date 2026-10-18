<?php
/**
 * The template for displaying 404 pages (not found)
 * 
 * @package Worprees_Shop
 */

get_header();

echo '<main id="main" class="site-main">';
echo '<section class="error-404 py-5">';
echo '<div class="container text-center">';

// 404 Title
echo '<h1>404</h1>';

// 404 Message
echo '<h2>' . esc_html__('صفحه یافت نشد', 'worprees-shop') . '</h2>';
echo '<p>' . esc_html__('متاسفانه صفحه مورد نظر شما یافت نشد یا حذف شده است', 'worprees-shop') . '</p>';

// 404 Actions
echo '<div class="error-404-actions">';

// Home Button
echo '<a href="' . esc_url(home_url('/')) . '" class="btn btn-primary btn-lg">';
echo '<i class="fas fa-home"></i> ' . esc_html__('بازگشت به صفحه اصلی', 'worprees-shop');
echo '</a>';

// Search Form
echo '<div class="error-404-search mt-4">';
echo '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
echo '<label for="error-search-input" class="screen-reader-text">' . esc_html__('جستجو:', 'worprees-shop') . '</label>';
echo '<input type="search" id="error-search-input" class="search-field" placeholder="' . esc_attr__('جستجو...', 'worprees-shop') . '" value="" name="s">';
echo '<button type="submit" class="search-submit">';
echo '<i class="fas fa-search"></i>';
echo '</button>';
echo '</form>';
echo '</div>';

echo '</div>';
echo '</div>';
echo '</section>';
echo '</main>';

get_footer();
