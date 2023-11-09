export declare function buildAudioFile(audioData: Uint8Array, audioOptions: {
    numChannels: number;
    sampleRate: number;
    bytesPerSample: number;
    subchunk2Size: number;
}): Uint8Array;
export declare function buildWaveHeader(opts: {
    numChannels: number;
    sampleRate: number;
    bytesPerSample: number;
    subchunk2Size: number;
}): ArrayBuffer;
