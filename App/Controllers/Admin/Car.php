<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 09.12.2017
 * Time: 13:10
 */

namespace IhorRadchenko\App\Controllers\Admin;

use IhorRadchenko\App\Controllers\Admin;
use IhorRadchenko\App\Exceptions\Error404;
use IhorRadchenko\App\Models\Brand;
use IhorRadchenko\App\Models\Car as CarModel;
use IhorRadchenko\App\Models\User;
use IhorRadchenko\App\View;

class Car extends Admin
{
    private $mainPage;

    /**
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionIndex()
    {
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/cars', CarModel::findPerPage($_POST['page'], 5));
            exit();
        }
        $this->header->page->title .= ' | Авто';
        $this->header->breadcrumb = ['main' => 'Авто'];
        $this->mainPage = new View('/App/templates/admin/cars.phtml');
        $this->mainPage->brands = Brand::findAll(false, 'name');
        $this->mainPage->cars = CarModel::findPerPage(1, 5);
        $this->mainPage->totalPages = ceil(CarModel::getAllCount() / 5);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }

    /**
     * @param $page
     * @param string $brand
     * @throws Error404
     * @throws \IhorRadchenko\App\Exceptions\DbException
     */
    protected function actionMark($page, string $brand)
    {
        $brand = ucwords(str_replace('-', ' ', $brand));
        if ($this->isAjax() && isset($_POST['page'])) {
            View::loadForAjax('admin/cars', CarModel::findCarsByBrandPerPage($brand, $_POST['page'], 5));
            exit();
        }
        if (! Brand::findOneByMark($brand)) {
            throw new Error404();
        }
        $this->header->page->title .= ' | Авто';
        $this->header->breadcrumb = ['main' => 'Авто'];
        $this->mainPage = new View('/App/templates/admin/cars.phtml');
        $this->mainPage->brands = Brand::findAll(false, 'name');
        $this->mainPage->cars = CarModel::findCarsByBrandPerPage($brand ,1, 5);
        $this->mainPage->totalPages = ceil(CarModel::getCountCarByBrand($brand) / 5);
        View::display($this->header, $this->sideBar, $this->mainPage, $this->footer);
    }
}