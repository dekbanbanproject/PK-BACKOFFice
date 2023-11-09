import GcPdfViewer from "../GcPdfViewer";
import { XfaForm } from "./XfaForm";
import { XfaNode } from "./XfaNode";
export declare class XfaApiImpl {
    viewer: GcPdfViewer;
    private _form;
    constructor(viewer: GcPdfViewer);
    get host(): XfaApiImpl;
    get form(): XfaForm;
    get soPrintFunctions(): any;
    resolveNode(nodeName: string): XfaNode;
    alert(cMsg: string | any, nIcon?: number, nType?: number, cTitle?: string, oDoc?: any, oCheckbox?: any): number;
    messageBox(cMsg: string | any, nIcon?: number, nType?: number, cTitle?: string, oDoc?: any, oCheckbox?: any): number;
    print(): void;
    resetData(): void;
    setFocus(xfaNode: XfaNode): void;
    beep(param: number): void;
    execMenuItem(menuItem: string): Promise<any>;
}
