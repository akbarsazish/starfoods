<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Profile;
use App\Http\Controllers\ListFactors;
use App\Http\Controllers\SubGroupFavorit;
use App\Http\Controllers\Message;
use App\Http\Controllers\GroupPart;
use App\Http\Controllers\Carts;
use App\Http\Controllers\ContactUs;
use App\Http\Controllers\Alarms;
use App\Http\Controllers\BagCash;
use App\Http\Controllers\Logout;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kala;
use App\Http\Controllers\Group;
use App\Http\Controllers\SubGroup;
use App\Http\Controllers\Shipping;
use App\Http\Controllers\HomePartType;
use App\Http\Controllers\HomePart;
use App\Http\Controllers\Brand;
use App\Http\Controllers\CustomerController;
use App\Http\Middleware\ChecUser;
use App\Http\Controllers\PreBuyFactorAfter;
use App\Http\Controllers\PreBuyOrderAfter;
use App\Http\Controllers\PreBuyOrder;
use App\Http\Controllers\Lottery;
use App\Http\Controllers\SaveEarth;
use App\Http\Controllers\BaseBonus;
use App\Http\Controllers\SalesOrder;
use App\Http\Controllers\OnlineAccount;

// \Artisan::call('route:clear');
// \Artisan::call('config:cache');
// \Artisan::call('optimize');
Route::get('/messages',[Admin::class,'messages'])->middleware('checkAdmin');
Route::get('/getMessages',[Admin::class,'getMessages'])->middleware('checkAdmin');
Route::get('/restrictCustomer',[Admin::class,'restrictCustomer'])->middleware('checkAdmin');
Route::get('/adminPage',[Admin::class,'index'])->middleware('checkAdmin');
Route::post('/customerProfile',[Admin::class,'customerProfile'])->middleware('checkAdmin');
Route::get('/dashboardAdmin',[Admin::class,'index'])->middleware('checkAdmin');
Route::get('/listKarbaran',[Admin::class,'listKarbaran'])->middleware('checkAdmin');
Route::get('/controlMainPage',[Admin::class,'controlMainPage'])->middleware('checkAdmin');
Route::get('/addNewGroup',[Admin::class,'addNewGroup'])->middleware('checkAdmin');
Route::get('/addAdmin',[Admin::class,'addAdmin'])->middleware('checkAdmin');
Route::post('/doAddAdmin',[Admin::class,'doAddAdmin'])->middleware('checkAdmin');
Route::post('/restrictAdmin',[Admin::class,'restrictAdmin'])->middleware('checkAdmin');
Route::post('/editAdmin',[Admin::class,'editAdmin'])->middleware('checkAdmin');
Route::post('/doEditAdmin',[Admin::class,'doEditAdmin'])->middleware('checkAdmin');
Route::get('/webSpecialSettings',[Admin::class,'webSpecialSettings'])->middleware('checkAdmin');
Route::post('/doUpdatewebSpecialSettings',[Admin::class,'doUpdatewebSpecialSettings'])->middleware('checkAdmin');
Route::get('/loginAdmin',[Admin::class,'loginAdmin']);
Route::post('/checkAdmin',[Admin::class,'checkAdmin']);
Route::get('/searchCustomer',[Admin::class,'searchCustomer'])->middleware('checkAdmin');
// Route::get('/notifyUser',[Admin::class,'notifyUser']);
Route::post('/doAddAlarm',[Alarms::class,'doAddAlarm']);
Route::get('/',[Home::class,'index']);
Route::get('/home',[Home::class,'index']);
Route::get('/profile', [Profile::class,'index'])->middleware('checkUser');
Route::get('/editProfile/{id}', [Profile::class, 'editMyprofile'])->middleware('checkUser');
Route::put('/customerUpdate/{id}', [Profile::class, 'customerUpdate'])->middleware('checkUser');
Route::get('/listFactors',[ListFactors::class,'index'])->middleware('checkUser');
Route::get('/listFavorits',[SubGroupFavorit::class,'index'])->middleware('checkUser');
Route::get('/messageList',[Message::class,'index'])->middleware('checkUser');
Route::get('/replayMessage',[Message::class,'replayMessage'])->middleware('checkAdmin');
Route::get('/carts',[Carts::class,'index'])->middleware('checkUser');
Route::post('/assignTakhfif',[Admin::class,'assignTakhfif'])->middleware('checkAdmin');
Route::get('/bagCash',[BagCash::class,'index'])->middleware('checkUser');
Route::get('/logout',[Logout::class,'logout']);
Route::get('/adminLogout',[Admin::class,'logout']);
Route::any('/login',[Logout::class,'login']);
Route::any('/Login',[Logout::class,'login']);
Route::post('/checkUser',[Logout::class,'checkUser']);
Route::post('/addpicture',[Kala::class,'changeKalaPic'])->middleware('checkAdmin');
Route::get('/getUnits',[Kala::class,'getUnits'])->middleware('checkUser');
Route::get('/getUnitsForPishKharid',[Kala::class,'getUnitsForPishKharid'])->middleware('checkUser');
Route::get('/getUnitsForUpdatePishKharid',[Kala::class,'getUnitsForUpdatePishKharid'])->middleware('checkUser');
Route::get('/getUnitsForUpdate',[Kala::class,'getUnitsForUpdate'])->middleware('checkUser');
Route::get('/updateOrderBYS',[Kala::class,'updateOrderBYS'])->middleware('checkUser');
Route::post('/deleteBYS',[Kala::class,'deleteBYS'])->middleware('checkUser');
 Route::post('/editKala',[Kala::class,'editKala'])->middleware('checkAdmin');
Route::post('/addMainGroup',[Group::class,'addGroup'])->middleware('checkAdmin');
Route::post('/deleteMainGroup',[Group::class,'deleteMainGroup'])->middleware('checkAdmin');
Route::post('/editMainGroup',[Group::class,'editMainGroup'])->middleware('checkAdmin');
Route::get('/listGroup',[Group::class,'index'])->middleware('checkAdmin');
Route::get('/editGroup',[Group::class,'editGroup'])->middleware('checkAdmin');
Route::get('/deleteGroup',[Group::class,'deleteGroup'])->middleware('checkAdmin');
Route::post('/deleteSubGroup',[SubGroup::class,'deleteSubGroup'])->middleware('checkAdmin');
Route::get('/listSubGroupKala',[SubGroup::class,'index'])->middleware('checkAdmin');
Route::get('/descKala/{groupId}/itemCode/{id}',[Kala::class,'descKala'])->middleware('checkUser');
Route::post('/shipping',[Shipping::class,'index'])->middleware('checkUser');
Route::post('/addFactor',[Shipping::class,'addFactor'])->middleware('checkUser');
Route::get('/starfoodFrom',[Shipping::class,'starfoodFromPayment'])->middleware('checkUser');
Route::get('/sucessPay',[Shipping::class,'successPay'])->middleware('checkUser');
Route::get('/wallet',[Shipping::class,'wallet'])->middleware('checkUser');
Route::get('/starfoodStar',[Shipping::class,'starfoodStar'])->middleware('checkUser');

Route::post('/cartView',[Carts::class,'cartView'])->middleware('checkUser');
Route::post('/factorView',[Carts::class,'factorView'])->middleware('checkUser');
Route::get('/allGroups',[Group::class,'mainGroups'])->middleware('checkUser');
Route::get('/listKala',[Kala::class,'index'])->middleware('checkAdmin');

Route::get('/listKalaFromPart/{id}/partPic/{picId}',[Kala::class,'listKalaFromPart'])->middleware('checkUser');
Route::get('/listKala/groupId/{id}',[Kala::class,'listSubKala'])->middleware('checkUser');
Route::get('/subGroups',[SubGroup::class,'subGroups'])->middleware('checkAdmin');
Route::post('/addSubGroup',[SubGroup::class,'addSubGroup'])->middleware('checkAdmin');
Route::post('/editSubgroup',[SubGroup::class,'editSubGroup'])->middleware('checkAdmin');
Route::get('/getKalaGroups',[SubGroup::class,'getKalaGroups'])->middleware('checkAdmin');
Route::get('/subGroupsEdit',[SubGroup::class,'subGroupsEdit'])->middleware('checkAdmin');
Route::get('/addKalaToSubGroups',[SubGroup::class,'addKalaToSubGroups'])->middleware('checkAdmin');
Route::get('/getPartType',[HomePartType::class,'getPartType'])->middleware('checkAdmin');
Route::get('/getListKala',[Kala::class,'getListKala'])->middleware('checkAdmin');
Route::get('/getListBrand',[Brand::class,'getListBrand'])->middleware('checkAdmin');
Route::post('/addKalaToGroup',[Kala::class,'addKalaToGroup'])->middleware('checkAdmin');
Route::get('/restrictSale',[Kala::class,'restrictSale'])->middleware('checkAdmin');
Route::get('/changeKalaPartPriority',[Kala::class,'changeKalaPartPriority'])->middleware('checkAdmin');
Route::get('/changeBrandPartPriority',[Brand::class,'changeBrandPartPriority'])->middleware('checkAdmin');
Route::get('/changeGroupsPartPriority',[Group::class,'changeGroupsPartPriority'])->middleware('checkAdmin');
Route::get('/getSelectedListKala',[Kala::class,'getSelectedListKala'])->middleware('checkAdmin');
Route::get('/buySomething',[Kala::class,'buySomething'])->middleware('checkUser');
Route::get('/buySomethingFromHome',[Kala::class,'buySomethingFromHome'])->middleware('checkUser');
Route::get('/preBuySomethingFromHome',[Kala::class,'preBuySomethingFromHome'])->middleware('checkUser');
Route::get('/updateOrderBYSFromHome',[Kala::class,'updateOrderBYSFromHome'])->middleware('checkUser');
Route::get('/updatePreOrderBYSFromHome',[Kala::class,'updatePreOrderBYSFromHome'])->middleware('checkUser');
Route::get('/pishKharidSomething',[Kala::class,'pishKharidSomething'])->middleware('checkUser');
Route::get('/updateOrderPishKharid',[Kala::class,'updateOrderPishKharid'])->middleware('checkUser');
Route::get('/setFavorite',[Kala::class,'setFavorite'])->middleware('checkUser');
Route::get('/doEditKalaPart',[HomePart::class,'doEditKalaPart'])->middleware('checkAdmin');
Route::post('/doEditGroupPart',[HomePart::class,'doEditGroupPart'])->middleware('checkAdmin');
Route::get('/getListGroup',[Group::class,'getListGroup'])->middleware('checkAdmin');
Route::get('/addKalaToPart',[HomePart::class,'addKalaToPart'])->middleware('checkAdmin');
Route::post('/editParts',[HomePart::class,'editParts'])->middleware('checkAdmin');
Route::get('/addOnePicToPart',[HomePart::class,'addOnePicToPart'])->middleware('checkUser');
Route::get('/addGroupToPart',[HomePart::class,'addGroupToPart'])->middleware('checkAdmin');
Route::post('/changePriority',[HomePart::class,'changePriority'])->middleware('checkAdmin');
Route::get('/getGroups',[HomePart::class,'getGroups'])->middleware('checkAdmin');
Route::get('/getAllKala/{id}',[HomePart::class,'getAllKala'])->middleware('checkUser');
Route::get('/getGroupSearch',[Group::class,'getGroupSearch'])->middleware('checkUser');
Route::get('/getSearchGroups',[Group::class,'getSearchGroups'])->middleware('checkUser');
Route::get('/searchGroups',[Group::class,'searchGroups'])->middleware('checkUser');
Route::get('/searchKalaByName',[Kala::class,'searchKalaByName'])->middleware('checkAdmin');
Route::get('/searchKalaByCode',[Kala::class,'searchKalaByCode'])->middleware('checkAdmin');
Route::get('/getSubGroupList',[SubGroup::class,'getSubGroupList'])->middleware('checkAdmin');
Route::get('/getSubGroup',[SubGroup::class,'getSubGroup'])->middleware('checkAdmin');
Route::get('/searchKalaBySubGroup',[Kala::class,'searchKalaBySubGroup'])->middleware('checkUser');
Route::get('/deletePart',[HomePart::class,'deletePart'])->middleware('checkAdmin');
Route::get('/getListKalaOnePic',[Kala::class,'getListKalaOnePic'])->middleware('checkAdmin');
Route::post('/addPart',[HomePart::class,'addPart'])->middleware('checkAdmin');
Route::get('/deleteKalaPart',[HomePart::class,'deleteKalaPart'])->middleware('checkUser');
Route::get('/addKalaPart',[HomePart::class,'addKalaPart'])->middleware('checkUser');
Route::get('/getKalas',[HomePart::class,'getKalas'])->middleware('checkUser');
Route::get('/getKalasSearch',[Kala::class,'getKalasSearch'])->middleware('checkAdmin');
Route::get('/getKalasSearchSubGroup',[Kala::class,'getKalasSearchSubGroup'])->middleware('checkAdmin');
Route::get('/searchKalas',[Kala::class,'searchKalas'])->middleware('checkAdmin');
Route::get('/searchKala/{name}',[Kala::class,'searchKala'])->middleware('checkUser');
Route::get('/changePicturesKalaPriority',[Kala::class,'changePicturesKalaPriority'])->middleware('checkUser');
Route::get('/deleteGroupPart',[HomePart::class,'deleteGroupPart'])->middleware('checkUser');
Route::get('/deleteKalaFromSubGroups',[SubGroup::class,'deleteKalaFromSubGroups'])->middleware('checkUser');
Route::get('/mainGroup/{groupId}/subGroup/{subGroupId}',[SubGroup::class,'getSecondSubGroup'])->middleware('checkUser');
Route::get('/getFilteredSecondSubGroup/{groupId}/subGroup/{subGroupId}',[SubGroup::class,'getFilteredSecondSubGroup'])->middleware('checkUser');
Route::get('/addMessage',[Message::class,'addMessage'])->middleware('checkUser');
Route::get('/doAddMessage',[Message::class,'doAddMessage'])->middleware('checkUser');
Route::post('/addDescKala',[Kala::class,'setDescribeKala'])->middleware('checkAdmin');
Route::post('/updateChangedPrice',[Kala::class,'updateChangedPrice'])->middleware('checkUser');
Route::get('/customerConfirmation1',[Logout::class,'customerConfirmation1']);
Route::post('/logOutConfirm',[Logout::class,'logOutConfirm']);
Route::get('/getPriorityList',[HomePart::class,'getPriorityList'])->middleware('checkAdmin');
Route::get('/getSubGroupKala',[Kala::class,'getSubGroupKala'])->middleware('checkAdmin');
Route::get('/getMainGroupList',[Group::class,'searchGroups'])->middleware('checkAdmin');
Route::get('/getListOfSubGroup',[Group::class,'getListOfSubGroup'])->middleware('checkAdmin');
Route::get('/tempRoute',[Kala::class,'tempRoute'])->middleware('checkAdmin');
Route::get('/changeMainGroupPriority',[Group::class,'changeMainGroupPriority'])->middleware('checkAdmin');
Route::get('/changeGroupPartPriority',[HomePart::class,'changeGroupPartPriority'])->middleware('checkAdmin');
Route::get('/changeSubGroupPriority',[SubGroup::class,'changeSubGroupPriority'])->middleware('checkAdmin');
Route::get('/searchSubGroupKala',[SubGroup::class,'searchSubGroupKala'])->middleware('checkAdmin');
Route::get('/getUnitsForSettingMinSale',[Kala::class,'getUnitsForSettingMinSale'])->middleware('checkAdmin');
Route::get('/getUnitsForSettingMaxSale',[Kala::class,'getUnitsForSettingMaxSale'])->middleware('checkAdmin');
Route::get('/setMinimamSaleKala',[Kala::class,'setMinimamSaleKala'])->middleware('checkAdmin');
Route::get('/setMaximamSaleKala',[Kala::class,'setMaximamSaleKala'])->middleware('checkAdmin');
Route::get('/getAllKalas',[Kala::class,'getAllKalas'])->middleware('checkAdmin');
Route::get('/addKalaToList',[Kala::class,'addKalaToList'])->middleware('checkAdmin');
Route::post('/addStockToList',[Kala::class,'addStockToList'])->middleware('checkAdmin');
Route::get('/addOrDeleteKalaFromSubGroup',[Kala::class,'addOrDeleteKalaFromSubGroup'])->middleware('checkAdmin');

Route::get('/getKalaWithPic/{id}',[Kala::class,'getKalaWithPic'])->middleware('checkAdmin');
Route::get('/changePriceKala',[Kala::class,'changePriceKala'])->middleware('checkAdmin');
Route::get('/getStocks',[Kala::class,'getStocks'])->middleware('checkAdmin');
Route::get('/searchKalaByStock',[Kala::class,'searchKalaByStock'])->middleware('checkAdmin');
Route::get('/searchKalaByExisanceOnStock',[Kala::class,'searchKalaByExisanceOnStock'])->middleware('checkAdmin');
Route::get('/getMainGroups',[Group::class,'getMainGroups'])->middleware('checkAdmin');
Route::get('/getSubGroups',[SubGroup::class,'getSubGroups'])->middleware('checkAdmin');
Route::get('/getKalaBySubGroups',[Kala::class,'getKalaBySubGroups'])->middleware('checkAdmin');
Route::get('/listKala/brandCode/{code}',[Kala::class,'getKalaBybrandItem'])->middleware('checkUser');
Route::post('/viewOreders',[Carts::class,'getOrders'])->middleware('checkUser');
Route::get('/listPictureName',[Kala::class,'listPictureName'])->middleware('checkUser');
Route::get('/brands',[Brand::class,'index'])->middleware('checkAdmin');
Route::post('/addBrand',[Brand::class,'addBrand'])->middleware('checkAdmin');
Route::post('/editBrand',[Brand::class,'editBrand'])->middleware('checkAdmin');
Route::post('/deleteBrand',[Brand::class,'deleteBrand'])->middleware('checkAdmin');
Route::get('/getBrandKala',[Brand::class,'getBrandKala'])->middleware('checkAdmin');
Route::post('/addKalaToBrand',[Brand::class,'addKalaToBrand'])->middleware('checkAdmin');
Route::get('/getKala',[Brand::class,'getKala'])->middleware('checkAdmin');
Route::post('/regularLogin',[CustomerController::class,'regularLogin'])->middleware('checkAdmin');
Route::post('/regularLogOut',[CustomerController::class,'regularLogOut'])->middleware('checkAdmin');
Route::get('/prebuyOrders',[PreBuyOrderAfter::class,'index']);
Route::get('/prebuyFactors',[PreBuyFactorAfter::class,'index']);
Route::get('/getOrders',[PreBuyOrderAfter::class,'index']);
Route::post('/sendFactorToApp',[PreBuyFactorAfter::class,'sendFactorToApp']);
Route::get('/deleteOrderPishKharid',[PreBuyOrder::class,'deleteOrderPishKharid']);
Route::get("/listPreBuys",[PreBuyFactorAfter::class,'listPreBuys']);
Route::get('/addCustomer', [CustomerController::class, 'addCustomer'])->middleware('checkUser');
Route::post('/doAddCustomer', [CustomerController::class, 'doAddCustomer'])->middleware('checkUser');
Route::post('/listOrders', [Profile::class, 'listOrders'])->middleware('checkUser');
Route::get('/addKalatoPart', [HomePart::class, 'addKalatoPart'])->middleware('checkAdmin');
Route::get('/deleteKalaFromPart', [HomePart::class, 'deleteKalaFromPart'])->middleware('checkAdmin');
Route::get('/getPrebuyAbles', [Kala::class, 'getPreBuyAbles'])->middleware('checkAdmin');
Route::get('/android/{userName}/{password}', [Logout::class, 'androidCall']);
Route::get('/androidLogin/{userName}/{password}', [Logout::class, 'androidLogin']);
// official customer route admin side
Route::get('/listCustomers',[Admin::class,'listCustomers'])->middleware('checkAdmin');
Route::post('/editCustomer',[Admin::class,'editCustomer'])->middleware('checkAdmin');
Route::get('/afterEditCustomer',[Admin::class,'afterEditCustomer'])->middleware('checkAdmin');
Route::get('/searchPreBuyAbleKalas',[Kala::class,'searchPreBuyAbleKalas'])->middleware('checkAdmin');

Route::get('/customerAdd/{id}', [CustomerController::class, 'haqiqiCustomerAdd'])->middleware('checkUser');
Route::get('/haqiqiCustomerAdmin/{id}', [CustomerController::class, 'haqiqiCustomerAdmin'])->middleware('checkUser');
Route::post('/storeHaqiqiCustomer', [CustomerController::class, 'storeHaqiqiCustomer'])->middleware('checkUser');
Route::post('/storeHaqiqiCustomerAdmin', [CustomerController::class, 'storeHaqiqiCustomerAdmin'])->middleware('checkAdmin');
Route::post('/storeHoqoqiCustomerAdmin', [CustomerController::class, 'storeHoqoqiCustomerAdmin'])->middleware('checkAdmin');
// The following routes for hoqoqi customer
Route::get('/hoqoqiCustomerList', [CustomerController::class, 'showHoqoqiCustomer'])->middleware('checkAdmin');
Route::get('/hoqoqiCustomerAdd', [CustomerController::class, 'hoqoqiCustomerAdd'])->middleware('checkUser');
Route::post('/storeHoqoqiCustomer', [CustomerController::class, 'storeHoqoqiCustomer'])->middleware('checkUser');
Route::get('/aboutUs', [Home::class, 'aboutUs'])->middleware('checkUser');
Route::get('/policy', [Home::class, 'policy'])->middleware('checkUser');
Route::get('/contactUs', [Home::class, 'contactUs'])->middleware('checkUser');
Route::get('/privacy', [Home::class, 'privacy'])->middleware('checkUser');
Route::get('/addRequestedProduct', [Kala::class, 'addRequestedProduct'])->middleware('checkUser');

Route::get('/displayRequestedKala', [Kala::class, 'displayRequestedKala'])->middleware('checkAdmin');
Route::get('/respondKalaRequest', [Kala::class, 'respondKalaRequest'])->middleware('checkAdmin');
Route::get('/cancelRequestedProduct', [Kala::class, 'cancelRequestedProduct'])->middleware('checkUser');
Route::get('/removeRequestedKala', [Kala::class, 'removeRequestedKala'])->middleware('checkAdmin');
Route::get('/searchRequestedKala', [Kala::class, 'searchRequestedKala'])->middleware('checkAdmin');
Route::get('/clearCache', [Admin::class, 'clearCache'])->middleware('checkAdmin');
Route::get('/crmLogin', [Admin::class, 'crmLogin']);
Route::get('/searchByMantagha', [Admin::class, 'searchByMantagha']);
Route::get('/searchByCity', [Admin::class, 'searchByCity']); 
Route::get('/searchCustomerByCode', [Admin::class,'searchCustomerByCode']);
Route::get('/searchCustomerByActivation', [Admin::class,'searchCustomerByActivation']);
Route::get('/searchCustomerLocationOrNot', [Admin::class,'searchCustomerLocationOrNot']);
Route::get('/searchMantagha', [Admin::class, 'searchMantagha']);
Route::get('/addMantiqah', [Admin::class, 'addMantiqah']);
Route::get('/addMasir', [Admin::class, 'addMasir']);
Route::get('/getMasirs', [Admin::class, 'getMasirs']);
Route::get('/takhsisMasirs', [Admin::class, 'takhsisMasirs']);
Route::get('/getAllCustomersToMNM', [Admin::class, 'getAllCustomersToMNM']);
Route::get('/searchCustomerByAddressMNM', [Admin::class, 'searchCustomerByAddressMNM']);
Route::get('/searchCustomerByNameMNM', [Admin::class, 'searchCustomerByNameMNM']);
Route::get('/searchCustomerByName', [Admin::class, 'searchCustomerByName']);
Route::get('/orderCustomers', [Admin::class, 'orderCustomers']);
Route::get('/searchCustomerAddedAddressMNM', [Admin::class, 'searchCustomerAddedAddressMNM']);
Route::get('/searchCustomerAddedNameMNM', [Admin::class, 'searchCustomerAddedNameMNM']);
Route::get('/addCustomerToMantiqah', [Admin::class, 'addCustomerToMantiqah']);
Route::get('/removeCustomerFromMantiqah', [Admin::class, 'removeCustomerFromMantiqah']);
Route::get('/getCityInfo', [Admin::class, 'getCityInfo']);
Route::get('/editCity', [Admin::class, 'editCity']);
Route::get('/addNewCity', [Admin::class, 'addCity']);
Route::get('/deleteCity', [Admin::class, 'deleteCity']);
Route::get('/getMantaghehInfo', [Admin::class, 'getMantaghehInfo']);
Route::get('/editMantagheh', [Admin::class, 'editMantagheh']);
Route::get('/deleteMantagheh', [Admin::class, 'deleteMantagheh']);
Route::get("/loginCrm",[Admin::class,'loginCrm2']);
Route::get("/getTenLastSales",[Kala::class,'getTenLastSales'])->middleware('checkAdmin');
Route::get("/customerDashboard",[CustomerController::class,'customerDashboard'])->middleware('checkAdmin');
Route::get("/getFactorDetail",[CustomerController::class,'getFactorDetail'])->middleware('checkAdmin');

Route::get("/getAdminInfo",[Admin::class,'getAdminInfo'])->middleware('checkAdmin');
Route::get('/constact',[ContactUs::class,'index'])->middleware('checkUser');
Route::get('/downloadApk',[Admin::class,'downloadApk']);
Route::get('/appguide',[Logout::class,'guide']);

// route for edit kala modal
Route::get('/editKalaModal',[Kala::class,'editKalaModal'])->middleware('checkAdmin');
//Route::get('/addDescKala',[Kala::class,'setDescribeKala'])->middleware('checkAdmin');


Route::get('/saveEarth',[saveEarth::class,'saveEarth']);
Route::get('/showLottery',[Lottery::class,'showLottery']);


Route::get('/cronaGame', function() {
    return view('game.radius.index');
  });

Route::get('/planetSave', function() {
    return view('game.planetDefense.planet');
  });

Route::get('/addGameScore',[SaveEarth::class,'addGameScore'])->middleware('checkUser');
Route::post('/addGamePrize',[Admin::class,'addGamePrize'])->middleware('checkAdmin');
Route::get('/emptyGame',[Admin::class,'emptyGame'])->middleware('checkAdmin');
Route::get('/baseBonusSettings',[BaseBonus::class,'baseBonusSetting'])->middleware('checkAdmin');
Route::get('/getTargetInfo',[BaseBonus::class,'getTargetInfo'])->middleware('checkAdmin');
Route::get("/editTarget",[BaseBonus::class,"editTarget"])->middleware('checkAdmin');
Route::get("/setCustomerLotteryHistory",[Lottery::class,"setCustomerLotteryHistory"])->middleware('checkUser');
Route::get("/editLotteryPrize",[Lottery::class,"editLotteryPrize"])->middleware('checkAdmin');
Route::get("/getLotteryInfo",[Lottery::class,"getLotteryInfo"])->middleware('checkAdmin');
Route::get("/lotteryResult",[Lottery::class,"lotteryResult"])->middleware('checkAdmin');
Route::post("/tasviyeahLottery",[Lottery::class,"tasviyeahLottery"])->middleware('checkAdmin');
Route::get("/setFactorSessions",[Shipping::class,"setFactorSessions"])->middleware('checkUser');

Route::get('/strayMaster',[SaveEarth::class,'strayMaster'])->middleware('checkUser');
Route::get('/towerGame',[SaveEarth::class,'towerGame'])->middleware('checkUser');


Route::get("/salesOrder",[SalesOrder::class,"salesOrder"])->middleware('checkAdmin');
Route::get("/getOrderDetail",[SalesOrder::class,"getOrderDetail"])->middleware('checkAdmin');
Route::get("/getSendItemInfo",[SalesOrder::class,"getSendItemInfo"])->middleware('checkAdmin');
Route::post("/addToFactorHisabdari",[SalesOrder::class,"addToFactorHisabdari"])->middleware('checkAdmin');
Route::get("/distroyOrder",[SalesOrder::class,"distroyOrder"])->middleware('checkAdmin');
Route::get("/deleteOrderItem",[SalesOrder::class,"deleteOrderItem"])->middleware('checkAdmin');
Route::get("/searchItemForAddToOrder",[SalesOrder::class,"searchItemForAddToOrder"])->middleware('checkAdmin');
Route::get("/getGoodInfoForAddOrderItem",[SalesOrder::class,"getGoodInfoForAddOrderItem"])->middleware('checkAdmin');
Route::get("/addToOrderItems",[SalesOrder::class,"addToOrderItems"])->middleware('checkAdmin');
Route::get("/getOrderItemInfo",[SalesOrder::class,"getOrderItemInfo"])->middleware('checkAdmin');
Route::get("/editOrderItem",[SalesOrder::class,"editOrderItem"])->middleware('checkAdmin');
Route::get("/getAllNewOrders",[SalesOrder::class,"getAllNewOrders"])->middleware('checkAdmin');
Route::get("/getAllRemainOrders",[SalesOrder::class,"getAllRemainOrders"])->middleware('checkAdmin');
Route::get("/getOrderFromDate",[SalesOrder::class,"getOrderFromDate"])->middleware('checkAdmin');
Route::get("/getOrderToDate",[SalesOrder::class,"getOrderToDate"])->middleware('checkAdmin');
Route::get("/getOrdersByCustCode",[SalesOrder::class,"getOrdersByCustCode"])->middleware('checkAdmin');
Route::get("/getOrderByCustName",[SalesOrder::class,"getOrderByCustName"])->middleware('checkAdmin');
Route::get("/getOrdersByPoshtibanCode",[SalesOrder::class,"getOrdersByPoshtibanCode"])->middleware('checkAdmin');
Route::get("/getOrdersByPoshtibanName",[SalesOrder::class,"getOrdersByPoshtibanName"])->middleware('checkAdmin');
Route::get("/checkOrderExistance",[SalesOrder::class,"checkOrderExistance"])->middleware('checkAdmin');
Route::get("/getPaymentInfo",[SalesOrder::class,"getPaymentInfo"])->middleware('checkAdmin');
Route::get("/getAllTodayOrders",[SalesOrder::class,"getAllTodayOrders"])->middleware('checkAdmin');
Route::get("/getAllSentOrders",[SalesOrder::class,"getAllSentOrders"])->middleware('checkAdmin');
Route::get("/editorderSendState",[SalesOrder::class,"editorderSendState"])->middleware('checkAdmin');
Route::get("/addMoneyToCase",[Shipping::class,"addMoneyToCase"])->middleware('checkUser');
Route::get("/addNazarSanji",[BaseBonus::class,"addNazarSanji"])->middleware('checkAdmin');

Route::get("/editNazar",[BaseBonus::class,"editNazar"])->middleware('checkAdmin');

Route::get("/editQuestion",[BaseBonus::class,"editQuestion"])->middleware('checkAdmin');

Route::get("/updateQuestion",[BaseBonus::class,"updateQuestion"])->middleware('checkAdmin');

Route::get("/getQAnswers",[BaseBonus::class,"getQAnswers"])->middleware('checkAdmin');
Route::get("/payedOnline",[OnlineAccount::class,"index"])->middleware('checkAdmin');
Route::get("/sendPayToHisabdari",[OnlineAccount::class,"sendPayToHisabdari"])->middleware('checkAdmin');
Route::get("/getPayedOnline",[OnlineAccount::class,"getPayedOnline"])->middleware('checkAdmin');