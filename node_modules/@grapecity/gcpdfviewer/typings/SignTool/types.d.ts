/// <reference path="../vendor/i18next.d.ts" />
//@ts-ignore
import { i18n } from "i18next";
import { SignToolSettings } from "../ViewerOptions";
import { SignToolStorage } from "./SignToolStorage";
export declare type SignToolType = 'Type' | 'Draw' | 'Image';
export declare type SignToolDialogModel = {
    autoResizeCanvas?: boolean;
    canvasSize?: {
        width: number;
        height: number;
    };
    enabled: boolean;
    hideSaveSignature?: boolean;
    isChanged: boolean;
    location?: 'Center' | 'Top' | 'Right' | 'Bottom' | 'Left' | 'TopLeft' | 'TopRight' | 'BottomRight' | 'BottomLeft' | {
        x: number;
        y: number;
    };
    pageIndex?: number;
    tabs?: ('Type' | 'Draw' | 'Image');
    title?: string;
    selectedTab?: SignToolType;
    saveSignature?: boolean;
    subject?: string;
    convertToContent?: boolean;
    showModal: boolean;
};
export declare type SignToolDialogProps = {};
export declare type SignToolModel = {
    penColor?: string;
    penWidth?: number;
};
export declare type BaseToolProps = {
    enabled: boolean;
    settings?: SignToolSettings;
    in17n: i18n;
    signToolStorage: SignToolStorage;
    canvasSize: {
        width: number;
        height: number;
    };
    onChanged: (isDirty: boolean) => void;
};
export declare type TypeToolModel = {
    textColor?: string;
    text?: string;
    fontSize?: number;
    fontName?: string;
    italic?: boolean;
    bold?: boolean;
};
export declare type ImageToolModel = {
    hasImage?: boolean;
};
