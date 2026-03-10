export type Curriculum = {
    id: number,
    name: string,
    curriculum_number: number,
    curriculum_code: string,
    content: string,
    checklist: string,
}

export type Chapter = {
    id: number;
    name: string;
    chapter_number: number;
    course_id: number;
    curricula: Curriculum[];
    course?:Course
};

export type Course = {
    id:number
    name:string
    course_code:number
    chapters:Chapter[]
}

export type Participant = {
    id: number
    name: string
};

export type ParticipantWithRelations = Participant & {
    participant_chapters: ParticipantChapter[];
    participant_curricula: ParticipantCurriculum[];
};

export type ChapterWithCourseName = Chapter & {
    courseName: string;
    isStarting?: boolean;
    completion_date?: date;
};

export type ParticipantChapter = {
    completion_date:date
    chapter: Chapter
    participantCurricula:ParticipantCurriculum[]
}

export type ParticipantCurriculum = {
    starting_date:date
    completion_date:date
    curriculum: Curriculum
    participant_chapter:ParticipantChapter
}

export type EditChapterWithCourseName = ChapterWithCourseName & {
    chapter_order:number
}

export type CurriculumWithCourseName = {
    chapter:Chapter
    curriculum: Curriculum
    courseName:string
    starting_date:date
    completion_date:date
}
