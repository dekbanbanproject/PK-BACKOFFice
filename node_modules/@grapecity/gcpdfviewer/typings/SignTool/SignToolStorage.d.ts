import { SignToolSettings } from "../ViewerOptions";
import SignaturePad from "./SignaturePad/SignaturePad";
import { SignToolType } from "./types";
export declare class SignToolStorage {
    ownerUserName: string;
    constructor(ownerUserName: string);
    get storageKey(): string;
    get settings(): SignToolSettings | undefined;
    setSetting(settingName: string, value: any): void;
    reset(): void;
    resetCanvasImages(): void;
    resetCanvasImage(toolType: SignToolType): void;
    saveCanvas(toolType: SignToolType, canvas: HTMLCanvasElement, signaturePad?: SignaturePad): void;
    saveImage(signToolType: SignToolType, img: HTMLImageElement): void;
    loadSignaturePad(signToolType: SignToolType, signaturePad: SignaturePad): Promise<boolean>;
    loadImage(signToolType: SignToolType, canvas: HTMLCanvasElement): Promise<HTMLImageElement | null>;
}
