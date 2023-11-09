export declare function rgbToHex(val: number[] | string): string;
export declare function hexToRgb(hex: string): number[] | null;
export declare function darkerColor(color: string | Uint8ClampedArray | number[], coef?: number): string | null;
export declare function enlightColor(color: string | Uint8ClampedArray | number[], englight?: number): string | null;
export declare function applyColorOpacity(color: string | Uint8ClampedArray | number[], opacity?: number): string | null;
export declare function toCssColor(colorVal?: string | number[] | Uint8ClampedArray | null, defaultCssVal?: string): string;
export declare function getLocalizedWebColorNames(in17n: any): Record<string, string>;
