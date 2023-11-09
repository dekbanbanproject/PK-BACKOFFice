//@ts-ignore
import { PropertyEditorProps } from "@grapecity/core-ui";
export declare type ColorEditorLocalization = {
    textStandardColors: string;
    textPalettes: string;
    textColorPicker: string;
    textWebColors: string;
    textOpacity: string;
    textWebColorNames: Record<string, string>;
    textHue: string;
    textSaturation: string;
    textLightness: string;
    textHex: string;
    textR: string;
    textG: string;
    textB: string;
};
export declare type ColorEditorProps = PropertyEditorProps & {
    opacity?: boolean;
    localization: ColorEditorLocalization;
};
