//@ts-ignore
import { PluginModel } from "@grapecity/viewer-core";
import { DrillthroughAction, InteractivityAction } from "../GcPdfDocument";
export declare type ReportEvent = PluginModel.ViewerEvent | {
    type: "ItemClicked";
    iStorePos: number;
    a: InteractivityAction;
};
export declare type ReportAction = InteractivityAction | DrillthroughAction | {
    Type: "print";
} | {
    Type: "download";
} | {
    Type: "save";
} | {
    Type: "rotate";
} | {
    Type: "text-selection";
} | {
    Type: "pan";
} | {
    Type: "doc-properties";
};
