<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::group(['middleware' => 'admin'], function () {


    Route::resource('agenda/devengo','DevengoController');
    Route::resource('agenda/acuerdo','AcuerdoController');
    Route::resource('agenda/vencimiento','VencimientoController');
    Route::resource('agenda/renovaciona','RenovacionController');
    Route::resource('agenda/promocion','ProspectobcController');
    Route::resource('agenda/gestor','GestorController');

    //proy_renovacion
    Route::resource('socioeconomico', 'SocioeconomicoController');
    Route::resource('agenda/renovacion', 'ProyRenovacionController');
    Route::get('inventario/{actividad}', 'SocioeconomicoController@inventario')->name('inventario');
    Route::get('informacion_socioeconomica/{cliente}','SocioeconomicoController@informacion')->name('informacion');
    Route::get('agenda/dataexcel/datos_renovacion','ExcelController@viewRenovacion')->name('datosrenovacion');
    Route::post('importarExcelInfoCred','ExcelController@importInfoCred');
    Route::post('importarExcelBlackList','ExcelController@importBlackList');
    Route::get('pdfrenovacion/{cliente}', 'PdfController@resultadosRenovacion')->name('pdfrenovacion');
    Route::post('calificar_oferta', 'SocioeconomicoController@calificarOferta')->name('califiaroferta');
    Route::get('calificar', 'SocioeconomicoController@calificarOfertaDos')->name('califiarofertados');
    Route::resource('cambiar_password','PasswordController');
    Route::resource('usuario','UserController');
    Route::get('agenda/resumen_avance','SocioeconomicoController@resumenAvance')->name('resumenAvance');
    Route::resource('perfil','PerfilesController');
    Route::get('perfil_usuario','UserController@perfiles')->name('listarPerfiles');
    Route::get('ofertas/{idCliente}', 'SocioeconomicoController@ofertas')->name('ofertas');
    Route::get('oferta_aceptada/{oferta}','SocioeconomicoController@ofertaAceptada')->name('ofertaAceptada');
    Route::get('verificar_oferta/{cliente}','SocioeconomicoController@verificarOferta')->name('verificarOferta');
    Route::resource('consulta', 'ConsultaController');
    Route::get('renovaciones_totales', 'ConsultaController@renovaciones')->name('renovaciones_totales');
    Route::get('ofertas_totales', 'ConsultaController@ofertas')->name('ofertas_totales');

    //Route::post('cerrar_sesion', 'LoginController@logout')->name('cerrarSesion');

    Route::post('agenda/agendadiaria','AgendaDiariaController@agendar');
    // PDF
    Route::get('pdfagenda', 'PdfController@getPdfagnd');
    Route::get('pdfvencimiento', 'PdfController@getPdfVenc');
    Route::get('pdfgestor', 'PdfController@getPdfGestor');
    //Reports
    Route::get('agenda/rptbi', 'RptbiController@index');
    Route::get('agenda/rptbi/rptagenda', 'RptbiController@rptAgenda');
    Route::get('agenda/rptbi/rptsesion', 'RptbiController@rptSesion');
    Route::get('agenda/rptbi/rptcartera', 'RptbiController@rptCartera');
    Route::get('agenda/rptbi/rptdevengo', 'RptbiController@rptDevengo');
    //Importacion
    Route::get('agenda/dataexcel', 'ExcelController@index');
    Route::get('agenda/dataexcel/importdevengo', 'ExcelController@viewDevengo');
    Route::get('agenda/dataexcel/importpago', 'ExcelController@viewPago');
    Route::post('importExcel', 'ExcelController@importsca');
    Route::post('importExcelsit', 'ExcelController@importsituacion');
    Route::post('importExcellc', 'ExcelController@importFliq');
    Route::post('importExcelrce', 'ExcelController@importFeje');
    Route::post('importExceldom', 'ExcelController@importDom');
    Route::post('importExceldev', 'ExcelController@importDev');
    Route::post('importExcelpago', 'ExcelController@importPago');
    Route::get('agenda/asesor/{id}','DevengoController@getAsesores');
    Route::get('agenda/gestores/{id}','GestorController@getGestores');
    //Proceso Operaciones
    Route::get('proceso/operacion','DashOperacionController@index');
    Route::get('execProcesoOperacion','DashOperacionController@execDashOper');
    //Cálculo de comisiones
    Route::get('proceso/operacion/comision','ComisionController@index');
    Route::get('execCalcCom','ComisionController@execCalcCom');
    //Proceso Devengos
    Route::get('proceso/devengo','DashOperacionController@devengo');
    Route::get('execRecupDevengo','DashOperacionController@execDev');
    //Descargas
    Route::get('agenda/dataexcel/downloadOpe', 'ExcelController@DashOperacion');
    Route::get('agenda/dataexcel/downloadCom', 'ExcelController@downCom');
    Route::get('agenda/dataexcel/downloadNoAbon', 'ExcelController@downNoAbonado');
    Route::get('agenda/dataexcel/downloadInact', 'ExcelController@downInactivo');
    Route::get('agenda/dataexcel/downloadDevV', 'ExcelController@downDevVenc');
    Route::get('agenda/dataexcel/downloadDevP', 'ExcelController@downDevParc');
});

Route::post('iniciar_sesion', 'LoginController@login')->name('iniciarSesion');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
