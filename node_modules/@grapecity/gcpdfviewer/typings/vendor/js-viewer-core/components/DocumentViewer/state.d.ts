import { DocViewMsg, DocViewModel, ViewSettings, SettingsMsg, Model } from "./types";
export declare function init(): Model;
export declare function initDocView(): DocViewModel;
export declare function initSettings(): ViewSettings;
export declare function docViewReducer(msg: DocViewMsg, model: DocViewModel): DocViewModel;
export declare function settingsReducer(msg: SettingsMsg, model: ViewSettings): ViewSettings;
