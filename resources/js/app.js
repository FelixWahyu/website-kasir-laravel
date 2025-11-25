import "./bootstrap";
import { openConfirmModal } from "./modal";
window.openConfirmModal = openConfirmModal;
import "./alert";
import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
