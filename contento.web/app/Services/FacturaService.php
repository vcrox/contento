<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Concepto;
use App\Models\Factura;
use App\Models\RegimenFiscal;
use App\Models\Ticket;
use App\Models\UsoCfdi;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Cfdi;
use CfdiUtils\CfdiCreator40;
use CfdiUtils\TimbreFiscalDigital\TfdCadenaDeOrigen;
use CfdiUtils\Utils\Format;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpCfdi\Finkok\FinkokEnvironment;
use PhpCfdi\Finkok\FinkokSettings;
use PhpCfdi\Finkok\QuickFinkok;
use Illuminate\Support\Str;
use PhpCfdi\Finkok\Services\Stamping\StampingResult;
use App\Utils\ConvertirALetra;
use App\Utils\GenerarCodigoQR;
use Illuminate\Support\Facades\Storage;
use PhpCfdi\Credentials\Certificate;
use PhpCfdi\Credentials\Credential;
use PhpCfdi\Credentials\PrivateKey;
use PhpCfdi\XmlCancelacion\Models\CancelDocument;
use PhpCfdi\XmlCancelacion\Models\CancelReason;
use PhpCfdi\XmlCancelacion\Models\Uuid;

class FacturaService
{
    public function List($q, $sortBy, $sortAsc, $pageNumber): LengthAwarePaginator
    {
        $facturas = Factura::when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('folio', 'like', '%' . $q . '%')
                    ->orWhere('serie', 'like', '%' . $q . '%')
                    ->orWhere('uuid', 'like', '%' . $q . '%')
                    ->orWhere('estatus', 'like', '%' . $q . '%')
                    ->orWhere('total', 'like', '%' . $q . '%')
                    ->orWhere('fecha_emision', 'like', '%' . $q . '%')
                    ->orWhereHas('cliente', function ($query) use ($q) {
                        $query->where('razon_social', 'like', '%' . $q . '%');
                    })
                    ->orWhereHas('tickets', function ($query) use ($q) {
                        $query->whereHas('cliente', function ($query) use ($q) {
                            $query->where('razon_social', 'like', '%' . $q . '%');
                        })->orWhereHas('formapago', function ($query) use ($q) {
                            $query->where('descripcion', 'like', '%' . $q . '%');
                        })->orWhere('referencia', 'like', '%' . $q . '%')
                            ->orWhere('codigo', 'like', '%' . $q . '%');
                    });
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC');
        return $facturas->paginate($pageNumber);
    }
    public function ExportList($q, $sortBy, $sortAsc): Collection
    {
        $facturas = Factura::when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('folio', 'like', '%' . $q . '%')
                    ->orWhere('serie', 'like', '%' . $q . '%')
                    ->orWhere('uuid', 'like', '%' . $q . '%')
                    ->orWhere('estatus', 'like', '%' . $q . '%')
                    ->orWhere('total', 'like', '%' . $q . '%')
                    ->orWhere('fecha_emision', 'like', '%' . $q . '%')
                    ->orWhereHas('cliente', function ($query) use ($q) {
                        $query->where('razon_social', 'like', '%' . $q . '%');
                    })
                    ->orWhereHas('tickets', function ($query) use ($q) {
                        $query->whereHas('cliente', function ($query) use ($q) {
                            $query->where('razon_social', 'like', '%' . $q . '%');
                        })->orWhereHas('formapago', function ($query) use ($q) {
                            $query->where('descripcion', 'like', '%' . $q . '%');
                        })->orWhere('referencia', 'like', '%' . $q . '%')
                            ->orWhere('codigo', 'like', '%' . $q . '%');
                    });
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')->get();
        return $facturas;
    }
    public function ListCliente($cliente_id, $q, $sortBy, $sortAsc, $pageNumber): LengthAwarePaginator
    {
        $facturas = Factura::whereHas('tickets', function ($query) use ($cliente_id) {
            return $query->where('cliente_id', $cliente_id);
        })->when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('folio', 'like', '%' . $q . '%')
                    ->orWhere('serie', 'like', '%' . $q . '%')
                    ->orWhere('uuid', 'like', '%' . $q . '%')
                    ->orWhere('estatus', 'like', '%' . $q . '%')
                    ->orWhere('total', 'like', '%' . $q . '%')
                    ->orWhere('fecha_emision', 'like', '%' . $q . '%')
                    ->orWhereHas('cliente', function ($query) use ($q) {
                        $query->where('razon_social', 'like', '%' . $q . '%');
                    })
                    ->orWhereHas('tickets', function ($query) use ($q) {
                        $query->whereHas('cliente', function ($query) use ($q) {
                            $query->where('razon_social', 'like', '%' . $q . '%');
                        })
                            ->orWhereHas('formapago', function ($query) use ($q) {
                                $query->where('descripcion', 'like', '%' . $q . '%');
                            })->orWhere('referencia', 'like', '%' . $q . '%')
                            ->orWhere('codigo', 'like', '%' . $q . '%');
                    });
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC');
        return $facturas->paginate($pageNumber);
    }
    public function ExportListCliente($cliente_id, $q, $sortBy, $sortAsc): Collection
    {
        $facturas = Factura::whereHas('tickets', function ($query) use ($cliente_id) {
            return $query->where('cliente_id', $cliente_id);
        })->when($q, function ($query) use ($q) {
            return $query->where(function ($query) use ($q) {
                $query->where('folio', 'like', '%' . $q . '%')
                    ->orWhere('serie', 'like', '%' . $q . '%')
                    ->orWhere('uuid', 'like', '%' . $q . '%')
                    ->orWhere('estatus', 'like', '%' . $q . '%')
                    ->orWhere('total', 'like', '%' . $q . '%')
                    ->orWhere('fecha_emision', 'like', '%' . $q . '%')
                    ->orWhereHas('cliente', function ($query) use ($q) {
                        $query->where('razon_social', 'like', '%' . $q . '%');
                    })
                    ->orWhereHas('tickets', function ($query) use ($q) {
                        $query->whereHas('cliente', function ($query) use ($q) {
                            $query->where('razon_social', 'like', '%' . $q . '%');
                        })->orWhereHas('formapago', function ($query) use ($q) {
                            $query->where('descripcion', 'like', '%' . $q . '%');
                        })->orWhere('referencia', 'like', '%' . $q . '%')
                            ->orWhere('codigo', 'like', '%' . $q . '%');
                    });
            });
        })
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')->get();
        return $facturas;
    }

    public function cancelStamp(Factura $factura, $motivo, $motivoSat)
    {
        $certificado = new Certificate($factura->tickets()->first()->cliente->file_cer_pem);
        $privateKey = new PrivateKey($factura->tickets()->first()->cliente->file_key_enc, env('PASSWORD_PAC', ''));
        $settings = new FinkokSettings(env('USER_PAC', ''), env('PASSWORD_PAC', ''), $factura->tickets()->first()->cliente->timbrado_produccion ? FinkokEnvironment::makeProduction() : FinkokEnvironment::makeDevelopment());
        $finkok = new QuickFinkok($settings);

        $document = null;
        switch ($motivoSat) {
            case '02':
                $document = CancelDocument::newWithErrorsUnrelated($factura->uuid);
                break;
            case '03':
                $document = CancelDocument::newNotExecuted($factura->uuid);
                break;
            default:
                break;
        }

        $cancelResult = $finkok->cancel(new Credential($certificado, $privateKey), $document);
        $detailDocument = $cancelResult->documents()->first();
        if ($detailDocument->documentStatus() != "201" && $detailDocument->documentStatus() != "202") {
            throw new Exception('Error al cancelar, error: ' . $detailDocument->documentStatus() . ':' . $detailDocument->cancellationStatus());
        }
        $factura->fecha_cancelado = $cancelResult->date();
        $factura->motivo_cancelado = 'Motivo SAT:' . $document->reason() . " Motivo Interno: " . $motivo;
        $factura->estatus = "CANCELADO";
        foreach ($factura->tickets as $ticket) {
            $ticket->estatus = "PENDIENTE";
            $ticket->save();
        }
        $factura->save();
        // $cancelRequest = $finkok->obtainCancelRequestReceipt($factura->ticket->cliente->rfc, $factura->uuid);
        // dd($cancelRequest->receipt());
    }
    public function Stamp(Cliente $cliente, Ticket $ticket, UsoCfdi $usocfdi, Concepto $concepto): StampingResult
    {
        if ($ticket->estatus != "PENDIENTE") {
            throw new Exception("El ticket no puede ser facturado");
        }
        if (!$ticket->cliente->file_cer || !$ticket->cliente->file_key_pem) {
            throw new Exception('No tiene capturados los archivos cer y key o son incorrectos');
        }
        if (!$ticket->cliente->serie) {
            throw new Exception('No tiene capturada la serie de la facturación');
        }
        if (!$ticket->cliente->folio) {
            throw new Exception('No tiene capturado el folio de la facturación');
        }

        $filename = $ticket->cliente->rfc . '_' . now()->format('dmYHis');
        $path_file_cer = $filename . '.cer';
        $path_file_key_pem = $filename . '.key.pem';
        Storage::put($path_file_cer, $ticket->cliente->file_cer);
        Storage::put($path_file_key_pem, $ticket->cliente->file_key_pem);
        $cerfile = Storage::path($path_file_cer); //$this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = Storage::path($path_file_key_pem); //$this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificado = new Certificado($cerfile);
        $fecha = now()->timestamp; // 2021-01-13 14:15:16

        $creator = new CfdiCreator40([
            'Serie' => $ticket->cliente->serie,
            'Folio' => $ticket->cliente->folio,
            'Fecha' => Format::datetime($fecha),
            'FormaPago' => $ticket->formapago->codigo, // efectivo
            'Moneda' => 'MXN',
            'TipoCambio' => '1', // Format::number(18.9008, 4), // taken from banxico
            'TipoDeComprobante' => "I", // ingreso
            'Exportacion' => '01', // No aplica
            'LugarExpedicion' => $ticket->cliente->codigopostal->codigo,
        ], $certificado);

        $comprobante = $creator->comprobante();
        $comprobante['MetodoPago'] = 'PUE'; // Pago en una sola exhibición
        $comprobante->addEmisor([
            'Nombre' => $ticket->cliente->razon_social,  // 'ESCUELA KEMPER URGATE',
            'RegimenFiscal' => $ticket->cliente->regimenfiscal->codigo, // General de Ley Personas Morales
            'Rfc' => $ticket->cliente->rfc,
        ]);

        $comprobante->addReceptor([
            'Rfc' => $cliente->rfc, //'EKU9003173C9',
            'Nombre' => $cliente->razon_social, //'ESCUELA KEMPER URGATE', // note is an "e" with accent
            'UsoCFDI' => $usocfdi->codigo, // 'G01', // Adquisición de mercancías
            'RegimenFiscalReceptor' => $cliente->regimenfiscal->codigo, //'601', // Personas Físicas con Actividades Empresariales y Profesionales
            'DomicilioFiscalReceptor' => $cliente->codigopostal->codigo, //'20928',
        ]);

        $importe = str::replace(',', '', number_format($ticket->cantidad / 1.16, 4));
        $iva = $ticket->cantidad - $importe;
        // add concepto #1
        $concepto = $comprobante->addConcepto([
            'ClaveProdServ' => $concepto->producto->codigo, //'52161557', // Consola portátil de juegos de computador
            //'NoIdentificacion' =>'',  //'GAMEPAD007',
            'Cantidad' => '1',
            'ClaveUnidad' => $concepto->unidadconcepto->unidad->codigo,  //'H87', // Pieza
            'Unidad' => $concepto->unidadconcepto->descripcion, //'PIEZA',
            'Descripcion' => $concepto->descripcion, //'Portable tetris gamepad pro++',
            'ValorUnitario' => $importe,
            'Importe' => $importe,
            'Descuento' => '0',
            'ObjetoImp' => '02',
        ]);
        $concepto->addTraslado([
            'Base' => $importe,
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => $iva,
        ]);

        // add additional calculated information sumas sello
        $creator->addSumasConceptos(null, 2);
        $creator->addSello('file://' . $keyfile);

        // validate the comprobante and check it has no errors or warnings
        $asserts = $creator->validate();
        $xmlContents = $creator->asXml();

        $settings = new FinkokSettings(env('USER_PAC', ''), env('PASSWORD_PAC', ''), $ticket->cliente->timbrado_produccion ? FinkokEnvironment::makeProduction() : FinkokEnvironment::makeDevelopment());
        $finkok = new QuickFinkok($settings);

        // el PreCFDI a firmar, podría venir de CfdiUtils ;) $creator->asXml()
        $precfdi = $xmlContents; //  file_get_contents('precfdi-to-sign.xml');
        // file_put_contents(Str::uuid() . '.xml', $precfdi);

        $stampResult = $finkok->stamp($precfdi); // <- aquí contactamos a Finkok

        if ($stampResult->hasAlerts()) { // stamp es un objeto con propiedades nombradas
            $error = "";
            foreach ($stampResult->alerts() as $alert) {
                $error = $alert->id() . ' - ' . $alert->message();
            }
            throw new Exception($error);
            // session()->flash('errorRfc', $error);
        } else {
        }
        return $stampResult;
    }

    public function saveInvoice(Cliente $cliente, Ticket $ticket, UsoCfdi $usocfdi, Concepto $concepto, RegimenFiscal $regimen, $xml): Factura
    {

        $stampResult = Cfdi::newFromString($xml)->getQuickReader();
        $formatter = new ConvertirALetra();

        $cfdi = \CfdiUtils\Cfdi::newFromString($xml);
        $tfd = $cfdi->getNode()->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
        $tfdXmlString = \CfdiUtils\Nodes\XmlNodeUtils::nodeToXmlString($tfd);
        $builder = new TfdCadenaDeOrigen();
        $tfdCadenaOrigen = $builder->build($tfdXmlString);
        $parameters = \CfdiUtils\ConsultaCfdiSat\RequestParameters::createFromCfdi($cfdi);
        $qrCodeText = $parameters->expression();
        $qrGenerator = new GenerarCodigoQR();
        $qrCode = (string)$qrGenerator->generar($qrCodeText);
        // dd($stampResult->Complemento->TimbreFiscalDigital['FechaTimbrado']);
        $data = [
            'folio' => $stampResult['Folio'],
            'serie' => $stampResult['Serie'],
            'fecha_timbrado' => Str::replace('T', ' ', $stampResult->Complemento->TimbreFiscalDigital['FechaTimbrado']),
            'fecha_emision' => Str::replace('T', ' ', $stampResult['Fecha']),
            'total' => $stampResult['Total'],
            'sub_total' => $stampResult['SubTotal'],
            'iva16' => $stampResult->Impuestos['TotalImpuestosTrasladados'],
            // 'iva0',
            'base_iva16' => $stampResult['SubTotal'],
            // 'base_iva0',
            'descuento' => $stampResult['Descuento'],
            'xml' => $xml,
            'uuid' => $stampResult->Complemento->TimbreFiscalDigital['UUID'],
            'total_letra' => $formatter->convertir($stampResult['Total']),
            'sello_sat' => $stampResult->Complemento->TimbreFiscalDigital['SelloSAT'],
            'sello_cfd' => $stampResult->Complemento->TimbreFiscalDigital['SelloCFD'],
            'sello_emisor' => $stampResult['Sello'],
            'certificado_sat' => $stampResult->Complemento->TimbreFiscalDigital['NoCertificadoSAT'],
            'certificado_emisor' => $stampResult['NoCertificado'],
            'cadena_original' => '',
            'cadena_original_complemento' => $tfdCadenaOrigen,
            'estatus' => "ACTIVA",
            'version' => $stampResult['Version'],
            'qrcode' => base64_decode($qrCode),
            'cliente_id' => $cliente->id,
            'concepto_id' => $concepto->id,
            // 'ticket_id' => $ticket->id,
            'uso_cfdi_id' => $usocfdi->id,
            'regimen_fiscal_id' => $regimen->id,
        ];
        $factura = Factura::create($data);
        $ticket->facturas()->attach($factura);
        $ticket->estatus = "FACTURADO";
        $ticket->save();
        $ticket->cliente->folio = $ticket->cliente->folio + 1;
        $ticket->cliente->save();

        return $factura;
    }
    public function TestStamp()
    {
        $cerfile = 'C:\EKU\EKU9003173C9.cer'; //$this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = 'C:\EKU\EKU9003173C9.key.pem'; //$this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificado = new Certificado($cerfile);
        $fecha = mktime(14, 15, 16, 4, 23, 2023); // 2021-01-13 14:15:16

        // create comprobante using creator with attributes
        // did not set the XmlResolver then a new XmlResolver is created using the default location
        $creator = new CfdiCreator40([
            'Serie' => 'A',
            'Folio' => random_int(1, 100),
            'Fecha' => Format::datetime($fecha),
            'FormaPago' => '01', // efectivo
            'Moneda' => 'MXN',
            'TipoCambio' => '1', // Format::number(18.9008, 4), // taken from banxico
            'TipoDeComprobante' => "I", // ingreso
            'Exportacion' => '01', // No aplica
            'LugarExpedicion' => '20928',
        ], $certificado);

        $comprobante = $creator->comprobante();
        $comprobante['MetodoPago'] = 'PUE'; // Pago en una sola exhibición
        $comprobante->addEmisor([
            'Nombre' => 'ESCUELA KEMPER URGATE',
            'RegimenFiscal' => '601', // General de Ley Personas Morales
        ]);

        $comprobante->addReceptor([
            'Rfc' => 'EKU9003173C9',
            'Nombre' => 'ESCUELA KEMPER URGATE', // note is an "e" with accent
            'UsoCFDI' => 'G01', // Adquisición de mercancías
            'RegimenFiscalReceptor' => '601', // Personas Físicas con Actividades Empresariales y Profesionales
            'DomicilioFiscalReceptor' => '20928',
        ]);

        // add concepto #1
        $concepto = $comprobante->addConcepto([
            'ClaveProdServ' => '52161557', // Consola portátil de juegos de computador
            'NoIdentificacion' => 'GAMEPAD007',
            'Cantidad' => '4',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Portable tetris gamepad pro++',
            'ValorUnitario' => '500',
            'Importe' => '2000',
            'Descuento' => '500', // hot sale: take 4, pay only 3
            'ObjetoImp' => '02',
        ]);
        $concepto->addTraslado([
            'Base' => '1500',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '240',
        ]);
        // $concepto->multiInformacionAduanera(
        //     ['NumeroPedimento' => '17  24  3420  7010987'],
        //     ['NumeroPedimento' => '17  24  3420  7010123']
        // );

        // add concepto #2
        $comprobante->addConcepto([
            'ClaveProdServ' => '43211914', // Pantalla pasiva lcd
            'NoIdentificacion' => 'SCREEN5004',
            'Cantidad' => '1',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Pantalla led 3x4" con entrada HDMI',
            'ValorUnitario' => '1000',
            'Importe' => '1000',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Base' => '1000',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '160',
        ]);

        // concepto #3 (freight)
        $comprobante->addConcepto([
            // - Servicios de Transporte, Almacenaje y Correo
            //   - Manejo y embalaje de material
            //     - Servicios de manejo de materiales
            //       - Tarifa de los fletes
            'ClaveProdServ' => '78121603', // Tarifa de los fletes
            'NoIdentificacion' => 'FLETE-MX',
            'Cantidad' => '1',
            'ClaveUnidad' => 'E48', // Unidad de servicio
            'Unidad' => 'SERVICIO',
            'Descripcion' => 'Servicio de envío de mercancías',
            'ValorUnitario' => '300',
            'Importe' => '300',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Base' => '300',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '48',
        ]);

        // add additional calculated information sumas sello
        $creator->addSumasConceptos(null, 2);
        $creator->addSello('file://' . $keyfile);

        // validate the comprobante and check it has no errors or warnings
        $asserts = $creator->validate();
        $xmlContents = $creator->asXml();

        $settings = new FinkokSettings('vcrox@hotmail.com', 'Zxc321...', FinkokEnvironment::makeDevelopment());
        $finkok = new QuickFinkok($settings);

        // el PreCFDI a firmar, podría venir de CfdiUtils ;) $creator->asXml()
        $precfdi = $xmlContents; //  file_get_contents('precfdi-to-sign.xml');
        file_put_contents(Str::uuid() . '.xml', $precfdi);

        $stampResult = $finkok->stamp($precfdi); // <- aquí contactamos a Finkok

        if ($stampResult->hasAlerts()) { // stamp es un objeto con propiedades nombradas
            $error = "";
            foreach ($stampResult->alerts() as $alert) {
                $error = $alert->id() . ' - ' . $alert->message();
            }
            session()->flash('error', $error);
        } else {
            file_put_contents($stampResult->uuid() . '.xml', $stampResult->xml()); // CFDI firmado
            session()->flash('message', 'Ticket timbrado correctamente');
        }
    }
}
