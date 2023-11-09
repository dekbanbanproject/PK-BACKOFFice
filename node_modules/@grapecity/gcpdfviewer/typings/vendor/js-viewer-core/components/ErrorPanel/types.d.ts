import { PluginModel } from "../../api";
export declare type ErrorDetails = {
    readonly severity: PluginModel.ErrorSeverity;
    readonly message: string;
    readonly details: string;
};
export declare type Model = {
    readonly visible: boolean;
    readonly errors: Array<ErrorDetails>;
};
export declare type Msg = {
    type: "AddError";
    payload: PluginModel.ErrorMessage;
} | {
    type: "Clear";
    payload: {};
} | {
    type: "Close";
    payload: {};
};
