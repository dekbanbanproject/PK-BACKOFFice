import GcPdfViewer from "..";
import { ViewerOptions } from "../ViewerOptions";
export declare class DocumentOptions {
    constructor(clientID: string, options: ViewerOptions, docViewer: GcPdfViewer);
    clientID: string;
    friendlyFileName: string;
    fileUrl: string;
    fileName: string;
    password: string;
    userData?: any;
    userName: string;
}
