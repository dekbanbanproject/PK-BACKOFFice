import { Model, Msg, PanelInfo } from "./types";
export declare function prefilterItems(items: PanelInfo[], order: string[]): PanelInfo[];
export declare const init: () => Model;
export declare function reducer(msg: Msg, model: Model): Model;
