// カリキュラム
export type Curriculum = {
    id: number,
    name: string,
    curriculum_number: number,
    curriculum_code: string,
    content: string,
    checklist: string,
    // chapter?:Chapter,
}

// チャプター(章)
export type Chapter = {
    id: number;
    name: string;
    chapter_number: number;
    course_id: number;
    curricula: Curriculum[];
    course?:Course
};

// コース
export type Course = {
    id:number
    name:string
    course_code:number
    chapters:Chapter[]
}

// 受講者
export type Participant = {
    id: number
    name: string
};

// 一覧画面で使用
export type ParticipantWithRelations = Participant & {
    participant_chapters: ParticipantChapter[];
    participant_curricula: ParticipantCurriculum[];
};

// チャプターリストで使用
export type ChapterWithCourseName = Chapter & {
    courseName: string;
    isStarting?: boolean;
    // starting_date?: date
    completion_date?: date;
};

// 受講者の登録チャプター
export type ParticipantChapter = {
    participant_id:number
    starting_date:date
    completion_date:date
    chapter: Chapter
    participantCurricula:ParticipantCurriculum[]
}

// 受講者が受けた課題
export type ParticipantCurriculum = {
    id:number
    starting_date:date
    completion_date:date
    curriculum: Curriculum
    participant_chapter:ParticipantChapter
}

export type NextCurriculum = Curriculum & {
    chapter: Chapter;
};

export type ShowCurriculum = Curriculum & {
    chapter: ShowChapter
}
type ShowChapter = Chapter & {
    course:Course
}
// export type EditChapterWithCourseName = ChapterWithCourseName & {
//     chapter_order:number
// }

// export type CurriculumWithCourseName = {
//     chapter:Chapter
//     curriculum: Curriculum
//     courseName:string
//     starting_date:date
//     completion_date:date
// }
