import { loadComponents } from "@util";
import { loadCrud } from "@StartCrud";
import { loadVenta } from "@Venta";

const hiddenCols = [
  "id_venta",
  "id_cliente",
  "descripcion",
  "forma_pago",
  "tasa",
  "iva",
  "comision",
  "registro",
];
let productosSeleccionados = [];
loadComponents();
loadCrud("venta", hiddenCols, true);
