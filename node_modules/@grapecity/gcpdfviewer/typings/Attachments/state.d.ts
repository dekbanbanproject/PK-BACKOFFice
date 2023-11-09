import { AttachmentsMsg, AttachmentsModel } from './types';
export declare const init: () => AttachmentsModel;
declare const reducer: (message: AttachmentsMsg, model: AttachmentsModel) => AttachmentsModel;
export { reducer as update };
